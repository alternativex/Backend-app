<?php

use GrahamCampbell\Dropbox\DropboxManager;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class DropboxImportCommand extends LockedConcurrentCommand {

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'dropbox:import';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import statements from Dropbox.';

    protected $dropbox;
    protected $tempDir;

    const DATA_FILE = 'data.json';

    /**
     * Create a new command instance.
     *
     * @param DropboxManager $dropbox
     * @return \DropboxImportCommand
     */
    public function __construct(DropboxManager $dropbox) {
        parent::__construct();
        $this->dropbox = $dropbox;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function fireLocked() {
        try {
            $this->tempDir = storage_path('temp');
            $this->log('Running Dropbox import');
            $metadata = $this->dropbox->getMetadataWithChildren($this->getDropboxProcessPath());
            $dirList = $metadata['contents'];
            foreach ($dirList as $dir) {
                if ($dir['is_dir']) {
                    $this->processDirectory($dir);
                }
            }
        } catch(Exception $e) {
            $this->log('something bad happened: ' . get_class($e) . ' - ' . $e->getMessage()." FILE ".$e->getFile()." LINE ".$e->getLine());
            $this->sendErrorEmailToAdmin(['path' => $this->getDropboxProcessPath()], get_class($e) . ' - ' . $e->getMessage()." FILE ".$e->getFile()." LINE ".$e->getLine());
        }
    }

    private function getDropboxProcessPath()
    {
        return $this->dropbox->getConnectionConfig($this->dropbox->getDefaultConnection())["process_dir"];
    }

    private function getDropboxProcessedPath()
    {
        return $this->dropbox->getConnectionConfig($this->dropbox->getDefaultConnection())["processed_dir"];
    }

    private function processingEtlExists($dirName)
    {
        return MemcachedService::instance()->has($this->processingEtlKey($dirName));
    }

    private function deleteProcessingEtl($dirName)
    {
        return MemcachedService::instance()->delete($this->processingEtlKey($dirName));
    }

    private function lockProcessingEtl($dirName)
    {
        return MemcachedService::instance()->set($this->processingEtlKey($dirName), true, 1800);
    }

    private function processingEtlKey($dirName)
    {
        return $dirName.".processing_etl";
    }

    private function doneEtlKey($dirName)
    {
        return $dirName.".done";
    }

    private function doneExists($dirName)
    {
        return MemcachedService::instance()->has($this->doneEtlKey($dirName));
    }

    protected function processDirectory($dir) {
        try {
            $path = $dir['path'];
            $dirName = substr($path, strrpos($path, '/') + 1);

            if ($this->processingEtlExists($dirName)) {
                $this->log($this->processingEtlKey($dirName)." key exists, directory already processing");
                return;
            }

            $this->lockProcessingEtl($dirName);
            $this->log("locked folder with ".$this->processingEtlKey($dirName));

            if (!$this->doneExists($dirName)) {
                $this->log($this->doneEtlKey($dirName)." not found directory not processable yet, removed key ".$this->processingEtlKey($dirName));
    //                $this->dropbox->delete($directory . "/.processing_etl");
                $this->deleteProcessingEtl($dirName);
                return;
            }

            $this->log("Processing directory $path");

            $dropboxMetadata = $this->dropbox->getMetadataWithChildren($path);
            $fileList = $dropboxMetadata['contents'];

            $this->log('Read data.json');

            $dataFile = $this->getDataFile($path);
            $dataJson = $this->dropboxReadFile($dataFile);
            $data = json_decode($dataJson, true);

            $this->log('check authorization');
            if (isset($data['payeeId'])) {
                $token = $data['payeeId'];
                $authorized = AuthorizationToken::findByToken($token, "User");
            } else {
                $token = isset($data['companyId']) ? $data['companyId'] : $data['publisherId'];
                $authorized = AuthorizationToken::findByToken($token, "Company");
            }

            if (empty($authorized)){
                $this->log("authorization token failed");
                $this->deleteProcessingEtl($dirName);
                return;
            }

            if (isset($data['payeeId'])) {
                $payee = User::find($authorized->model_id);
                $companyId = $payee->company_id;
                $payeeCode = $payee->code;
            } else {
                $companyId = $authorized->model_id;
                $payeeCode = null;
            }
            $this->log("authorization succeeded");

            $publisherAdmins = User::publisherAdmins($companyId);

            $this->log('check credits');
            if (!Company::hasCredits($companyId)) {
                $this->log("no credits available");
                $this->deleteProcessingEtl($dirName);
                $this->sendEmailToPublisherAdminsNoCredits($publisherAdmins, $companyId);
                return;
            }
            $this->log("company has credits");

            if (true || !isset($data['dealId'])) {
                $this->log('No deal id found, creating deal');
                $dealName = (isset($data["dealName"]) && !empty($data["dealName"])) ? $data["dealName"] : $dirName;
                $deal = $this->createDeal($data, $dealName, $companyId, $payeeCode);
                $data['dealId'] = $deal->id;
                $this->log("Will upload to $dataFile");
                $this->dropbox->uploadFileFromString($dataFile, \Dropbox\WriteMode::force(),
                    json_encode($data));
            } else {
                $deal = Deal::find($data['dealId']);
            }

            $this->log('Deal id is ' . $deal->id);

            $statementList = $data['files'];
            foreach ($statementList as $filename => $metadata) {
                $this->processFile($filename, $metadata, $fileList, $deal, $path);
    //            break;
            }
            $deal->etl_status = 'processed';
            $deal->save();

            $decremented = Company::decrementCredits($companyId);
            $this->log("decrement credits ".$decremented);

            if (count($publisherAdmins)){
                $this->sendEmailToPublisherAdmins($publisherAdmins, $deal);
                $this->log("email sent to publisher");
            } else {
                $this->log("no publisher admins found");
            }

            $moveTo = $this->getDropboxProcessedPath().'/' . $dirName;
            $this->dropbox->move($path, $moveTo);
            $this->log("moved to $moveTo");

            $this->log("start executing rollups");
            RollupEvent::EXECUTE_ROLLUP_TABLES($deal->id);
            $this->log("end executing rollups");

            $this->deleteProcessingEtl($dirName);
            $this->log("folder unlocked");

        } catch(Dropbox\Exception $e) {
            $this->log('dropbox error: ' . get_class($e) . ' - ' . $e->getMessage()." FILE ".$e->getFile()." LINE ".$e->getLine());
            $this->deleteProcessingEtl($dirName);
            $this->log("folder unlocked");
            $this->sendErrorEmailToAdmin($dir, get_class($e) . ' - ' . $e->getMessage()." FILE ".$e->getFile()." LINE ".$e->getLine());
        } catch(Exception $e) {
            $this->log('something bad happened: ' . get_class($e) . ' - ' . $e->getMessage()." FILE ".$e->getFile()." LINE ".$e->getLine());
            $this->sendErrorEmailToAdmin($dir, get_class($e) . ' - ' . $e->getMessage()." FILE ".$e->getFile()." LINE ".$e->getLine());
        }
    }

    private function sendErrorEmailToAdmin($dir, $errorMessage)
    {
        Mail::send('emails.processing.admin', ['dir' => $dir['path'], 'errorMessage' => $errorMessage],
            function($message) {
                $message->from('no-reply@royaltyexchange.com')->
                    to(Config::get("mail.admins"))->
                    subject('[MINT NOTIFICATION - PUBLISHER ETL ERROR]');
            }
        );
    }

    private function sendEmailToPublisherAdminsNoCredits($publisherAdmins, $companyId)
    {
        $en = EmailNotification::findNotification("Company", $companyId, EmailNotification::COMPANY_NO_CREDIT);
        if (empty($en) || !$en->isValidNotification())
        {
            Mail::send('emails.import.no_credits', [],
                function($message) use ($publisherAdmins) {
                    $emails = array_map(function ($ar) {return $ar["email"];}, $publisherAdmins->toApiArray());
                    $message->from('no-reply@royaltyexchange.com')
                        ->to($emails)
                        ->bcc(Config::get("mail.admins"))
                        ->subject('[MINT NOTIFICATION - NO CREDITS LEFT]');
                }
            );
            EmailNotification::createUpdateNotification("Company", $companyId, EmailNotification::COMPANY_NO_CREDIT);
        }
    }

    private function sendEmailToPublisherAdmins($publisherAdmins, $deal)
    {
        Mail::send('emails.import.success', ['deal' => $deal],
            function($message) use ($publisherAdmins) {
                $emails = array_map(function ($ar) {return $ar["email"];}, $publisherAdmins->toApiArray());
                $message->from('no-reply@royaltyexchange.com')
                    ->to($emails)
                    ->bcc(Config::get("mail.admins"))
                    ->subject('[MINT NOTIFICATION - PUBLISHER ETL FINISHED]');
            }
        );
    }

    protected function fileExists($fileList, $filename) {
        foreach ($fileList as $file) {
            $path = $file['path'];
            if ($filename[0] === '/') { // it's a path
                if ($filename === $path) {
                    return true;
                }
            } else {
                $crtFilename = substr($path, strrpos($path, '/') + 1);
                if ($filename === $crtFilename) {
                    return true;
                }
            }
        }
        return false;
    }

    protected function getDataFile($path) {
        return $path . '/' . self::DATA_FILE;
    }

    protected function dropboxReadFile($filePath) {
        $this->log("Reading file $filePath");
        $filename = substr($filePath, strrpos($filePath, '/') + 1);
        $tempFile = $this->tempDir . '/' . $filename;
        $this->log("Temporary file is $tempFile");
        $file = fopen($tempFile, 'w+');
        $this->dropbox->getFile($filePath, $file);
        fclose($file);
        $result = file_get_contents($tempFile);
        unlink($tempFile);
        return $result;
    }

    protected function createDeal($data, $name, $company_id, $payeeCode=null)
    {
        $dealData = ['status'       => Deal::STATUS_UNREVIEWED,
                     'name'         => $name,
                     'payee_code'   => $payeeCode,
                     'etl_status'   => Deal::ETL_STATUS_PROCESSING,
                     'company_id'   => $company_id,
                     'percentage'   => 100,
        ];
        (isset($data['firstName']) && isset($data['lastName'])) ? $dealData['writer_name'] = $data['firstName'].' '.$data['lastName'] : null;
        isset($data['email']) ? $dealData['writer_email'] = $data['email'] : null;
        isset($data['phone']) ? $dealData['writer_phone'] = $data['phone'] : null;

        $deal = Deal::create($dealData);
        return $deal;
    }

    protected function quarterToMonth($quarter)
    {
        $quarterToMonth = [1 => 1, 2 => 4, 3 => 7, 4 => 10];
        if ($quarter > 4)
            $quarter = 4;
        return $quarterToMonth[$quarter];
    }

    protected function processFile($filename, $metadata, $fileList, $deal, $path) {
        $monthToQuarter = [1 => 1, 2 => 1, 3 => 1, 4 => 2, 5 => 2, 6 => 2,
            7 => 3, 8 => 3, 9 => 3, 10 => 4, 11 => 4, 12 => 4];


        $this->log("Processing file $filename");
        $csvFilename = sprintf('%s/%s.csv', $path, $filename);
        if ($this->fileExists($fileList, $csvFilename)) {

            /*
            * MESSAGE LEFT ON 4/14/15
            * For Ascap International and Domestic providers the files come together, meaning in the same processing
            * directory, we have Ascap International and Domestic files. We check the file name to see if contains
            * the word international or domestic and we execute etl with the correct provider.
            */
            $processingProviderId = $metadata['provider'];
            if ($processingProviderId == 3 && strpos($filename, 'Domestic') !== false)
                $processingProviderId = 50;
            if ($processingProviderId == 50 && strpos($filename, 'International') !== false)
                $processingProviderId = 3;

            $royaltyProvider = RoyaltyProvider::find($processingProviderId);
            $csvContent = $this->dropboxReadFile($csvFilename);
            $this->log('File size is ' . strlen($csvContent));
            $etlFolder = $royaltyProvider->php_etl_upload_location;
            if (!is_dir($etlFolder)) {
                $this->log("Create upload folder $etlFolder");
                mkdir($etlFolder, 0755, true);
                mkdir($etlFolder . '/LogFiles', 0755);
                mkdir($etlFolder . '/Processed', 0755);
            }
            $etlFile = sprintf('%s/%d_%s.csv', $etlFolder, $deal->id, $filename);
            $this->log("Writing $etlFile");
            file_put_contents($etlFile, $csvContent);

            $month = $metadata['month'];
            if (empty($month)) {
                $month = $this->quarterToMonth((int)$metadata['quarter']);
                $periodType = RoyaltyStreamFile::PERIOD_TYPE_QUARTER;
            }
            $quarter = $metadata['quarter'];
            if (empty($quarter)) {
                $quarter = $monthToQuarter[(int)$metadata['month']];
                $periodType = RoyaltyStreamFile::PERIOD_TYPE_MONTH;
            }
            $periodYearQuarter = sprintf('%dQ%d', $metadata['year'], $quarter);

            $hasPdf = false;
            if ($metadata['hasPdf']) {
                $pdfFilename = sprintf('%s/%s.pdf', $path, $filename);
                if ($this->fileExists($fileList, $pdfFilename)) {
                    $contents = $this->dropboxReadFile($pdfFilename);
                    $pdfFolder = storage_path('pdf');
                    $pdfFile = sprintf('%s/%d_%s.pdf', $pdfFolder, $deal->id, $filename);
                    $this->log("Saving PDF to $pdfFile");
                    file_put_contents($pdfFile, $contents);
                    $hasPdf = true;
                }
            }

            $share = isset($metadata['providerRoyaltyShare']) ? $metadata['providerRoyaltyShare'] : 1;
            $accountName = isset($metadata["providerAccountName"]) ? $metadata["providerAccountName"] : null;
            $rsf = RoyaltyStreamFile::create([
                'deal_id' => $deal->id,
                'stream_file_name' => $etlFile,
                'status' => 0,
                'period_year' => $metadata['year'],
                'period_month' => $month,
                'period_quarter' => $quarter,
                'company_id' => $deal->company_id,
                'royalty_provider_id' => $processingProviderId,
                'royalty_type_id' => $metadata['providerRoyaltyType'],
                'royalty_share_id' => $share,
                'period_type' => $periodType,
                'period_year_quarter' => $periodYearQuarter,
                'account_name' => $accountName,
                'has_pdf' => $hasPdf,
            ]);
            $this->log("saved royalty stream file ".$rsf->id);

            $etlCommand = $royaltyProvider->php_etl_command.' publisher '.$royaltyProvider->id.' "'.$etlFile.'"';
            $this->log("Executing $etlCommand");
            exec($etlCommand, $output, $status);
            $this->log('Output: ' . print_r($output, true));
            $this->log("Status: $status");
            $this->log("Delete etl file: $etlFile");
            unlink($etlFile);

            if ($output[0] != "finished")
                throw new Exception("Processing failed for command: ".$etlCommand." with output: ".$output[0]);
        }
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments() {
//        return array(
//            array('example', InputArgument::REQUIRED, 'An example argument.'),
//        );
        return [];
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions() {
//        return array(
//            array('example', null, InputOption::VALUE_OPTIONAL, 'An example option.', null),
//        );
        return [];
    }

}
