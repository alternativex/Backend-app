<?php

class RoyaltyPaymentFileServiceTest extends TestCase
{
    protected function modelsToReset()
    {
        return ["PayeeCompany", "Client", "RollupEvent", "RoyaltyPaymentFile", "User", "RoyaltyPayment", "PayeePayment", "Deal", "RoyaltyStream", "RoyaltyStreamFile"];
    }

    protected function copyFileToTemp($filename)
    {
        $tempFileName = $this->tempFileNamePath($filename);
        copy($this->filesTestingPath().$filename, $tempFileName);
        return $tempFileName;
    }

    protected function tempFileNamePath($filename)
    {
        return $this->tempFilePath()."/".$filename;
    }

    /**
     * @return RoyaltyPaymentFileService
     */
    private function service()
    {
        return App::make("RoyaltyPaymentFileService");
    }

    private function filePath()
    {
        return $this->filesTestingPath().$this->fileName();
    }

    private function filesTestingPath()
    {
        return dirname(__FILE__)."/files/";
    }

    private function tempFilePath()
    {
        return storage_path('temp');
    }

    private function fileName()
    {
        return "test_royalty_payment_file.csv";
    }

    public function testSaveRPF()
    {
        $this->resetEvents();
        $path = $this->filePath();
        $s = $this->service();
        $rpf = $s->saveRoyaltyPaymentFile($path, 1, ["year" => 2014, "month" => 12]);
        $this->assertTrue($rpf->company_id == 1);
        $this->assertTrue($rpf->id == 1);
        $this->assertTrue($rpf->path == $this->filePath());
        $this->assertTrue($rpf->name == $this->fileName());
        $this->resetEvents();
    }

    public function testSaveFile()
    {
        $this->resetEvents();
        $path = $this->filePath();
        $s = $this->service();
        $companyId = rand(1000, 1000000);
        $fileDetails1 = ["year" => 2010, "quarter" => 3];
        $fileDetails2 = ["year" => 2011, "month" => 11];

        $rpf = $s->saveFile($path, $companyId, $fileDetails1);
        $this->assertTrue($rpf->company_id == $companyId);
        $this->assertTrue($rpf->id == 1);
        $this->assertTrue($rpf->path == $this->filePath());
        $this->assertTrue($rpf->name == $this->fileName());
        $this->assertTrue($rpf->year == $fileDetails1["year"]);
        $this->assertTrue($rpf->quarter == $fileDetails1["quarter"]);
        $this->assertTrue($rpf->month == 0);

        $rpf = $s->saveFile($path, $companyId, $fileDetails2);
        $this->assertTrue($rpf->year == $fileDetails2["year"]);
        $this->assertTrue($rpf->quarter == 0);
        $this->assertTrue($rpf->month == $fileDetails2["month"]);

        $this->resetEvents();
    }

    public function testSavePayees()
    {
        $this->resetEvents();
        $path = $this->filePath();
        $s = $this->service();
        $companyId = rand(1000, 1000000);

        $rpf = $s->saveRoyaltyPaymentFile($path, $companyId, ["year" => 2015, "month" => 3]);
        $s->savePayments($rpf);

        $s->savePayees($rpf);

        $payees = User::all();
        $this->assertTrue(count($payees) == 2);
        $payee1 = $payees[0];
        $this->assertTrue($payee1->id == 1);
        $this->assertTrue($payee1->company_id == $companyId);
        $this->assertTrue($payee1->type == "payee");
        $this->assertTrue($payee1->email == null);
        $this->assertTrue($payee1->code == "1175");
        $this->assertTrue($payee1->name == "Studio 51");
        $payee2 = $payees[1];
        $this->assertTrue($payee2->id == 2);
        $this->assertTrue($payee2->company_id == $companyId);
        $this->assertTrue($payee2->type == "payee");
        $this->assertTrue($payee2->email == null);
        $this->assertTrue($payee2->code == "1379");
        $this->assertTrue($payee2->name == "Pop Virus (PACIFICA)");

        $this->resetEvents();
    }

    public function testSavePayeesAlreadyExisting()
    {
        $this->resetEvents();
        $path = $this->filePath();
        $s = $this->service();
        $companyId = rand(1000, 1000000);

        $rpf = $s->saveRoyaltyPaymentFile($path, $companyId, ["year" => 2015, "month" => 3]);
        $s->savePayments($rpf);

        $s->savePayees($rpf);

        $payees = User::all();
        $this->assertTrue(count($payees) == 2);
        $payee1 = $payees[0];
        $this->assertTrue($payee1->id == 1);
        $this->assertTrue($payee1->company_id == $companyId);
        $this->assertTrue($payee1->type == "payee");
        $this->assertTrue($payee1->email == null);
        $this->assertTrue($payee1->code == "1175");
        $this->assertTrue($payee1->name == "Studio 51");
        $payee2 = $payees[1];
        $this->assertTrue($payee2->id == 2);
        $this->assertTrue($payee2->company_id == $companyId);
        $this->assertTrue($payee2->type == "payee");
        $this->assertTrue($payee2->email == null);
        $this->assertTrue($payee2->code == "1379");
        $this->assertTrue($payee2->name == "Pop Virus (PACIFICA)");

        $payee1=User::find(1);
        $payee1->email = "test123@test.com";
        $payee1->save();
        $payee2=User::find(2);
        $payee2->email = "test234@test.com";
        $payee2->save();

        $s->savePayees($rpf);
        $payees = User::all();
        $this->assertTrue(count($payees) == 2);
        $payee1 = $payees[0];
        $this->assertTrue($payee1->id == 1);
        $this->assertTrue($payee1->company_id == $companyId);
        $this->assertTrue($payee1->type == "payee");
        $this->assertTrue($payee1->email == "test123@test.com");
        $this->assertTrue($payee1->code == "1175");
        $this->assertTrue($payee1->name == "Studio 51");
        $payee2 = $payees[1];
        $this->assertTrue($payee2->id == 2);
        $this->assertTrue($payee2->company_id == $companyId);
        $this->assertTrue($payee2->type == "payee");
        $this->assertTrue($payee2->email == "test234@test.com");
        $this->assertTrue($payee2->code == "1379");
        $this->assertTrue($payee2->name == "Pop Virus (PACIFICA)");

        $this->resetEvents();
    }

    public function testProcess()
    {
        $this->resetEvents();
        $path = $this->copyFileToTemp($this->fileName());
        $s = $this->service();
        $companyId = rand(1000, 1000000);

        $rpf = $s->process($path, $companyId, ["year" => 2015, "month" => 7]);
        $this->assertTrue($rpf->status == RoyaltyPaymentFile::STATUS_PAYMENTS_PROCESSED);

        $payees = User::all();
        $this->assertTrue(count($payees) == 2);
        $payee1 = $payees[0];
        $this->assertTrue($payee1->id == 1);
        $this->assertTrue($payee1->company_id == $companyId);
        $this->assertTrue($payee1->type == "payee");
        $this->assertTrue($payee1->email == null);
        $this->assertTrue($payee1->name == "Studio 51");
        $payee2 = $payees[1];
        $this->assertTrue($payee2->id == 2);
        $this->assertTrue($payee2->company_id == $companyId);
        $this->assertTrue($payee2->type == "payee");
        $this->assertTrue($payee2->email == null);
        $this->assertTrue($payee2->name == "Pop Virus (PACIFICA)");

        $rpf = RoyaltyPaymentFile::where("company_id", "=", $companyId)->first();
        $this->assertTrue($rpf->company_id == $companyId);
        $this->assertTrue($rpf->id == 1);
        $this->assertTrue($rpf->path == $this->tempFileNamePath($this->fileName()));
        $this->assertTrue($rpf->name == $this->fileName());

        $payeePayments = PayeePayment::all();
        $this->assertTrue(count($payeePayments) == 2);

        $this->assertTrue($payeePayments[0]->payment_date == null);
        $this->assertTrue($payeePayments[0]->status == "unpaid");

        $paymentsSum = RoyaltyPayment::where("payee_payment_id", "=", $payeePayments[0]->id)->select(DB::raw('sum(amount_received) AS amount'))->get()[0];
        $this->assertTrue($paymentsSum->amount == $payeePayments[0]->amount);

        $paymentsSum = RoyaltyPayment::where("payee_payment_id", "=", $payeePayments[1]->id)->select(DB::raw('sum(amount_received) AS amount'))->get()[0];
        $this->assertTrue($paymentsSum->amount == $payeePayments[1]->amount);

        $this->resetEvents();
    }

    public function testProcess14H1_Riptide_Royalty_Data_Selected()
    {
        $this->resetEvents();
        $fileName = "test_royalty_payment_file_large.csv";
        $path = $this->copyFileToTemp($fileName);
        $s = $this->service();
        $companyId = rand(1000, 1000000);

        $rpf = $s->process($path, $companyId, ["year" => 2015, "month" => 7]);
        $this->assertTrue($rpf->status == RoyaltyPaymentFile::STATUS_PAYMENTS_PROCESSED);

        $payments = RoyaltyPayment::all();
        $this->assertTrue(count($payments) == 5434);
        $payees = User::all();
        $this->assertTrue(count($payees) == 41);

        $rpf = RoyaltyPaymentFile::where("company_id", "=", $companyId)->first();
        $this->assertTrue($rpf->company_id == $companyId);
        $this->assertTrue($rpf->id == 1);
        $this->assertTrue($rpf->path == $path);
        $this->assertTrue($rpf->name == $fileName);
        $this->assertTrue($rpf->status == RoyaltyPaymentFile::STATUS_PAYMENTS_PROCESSED);

        $this->resetEvents();
    }

    public function testSavePayeeUnpaidPayments()
    {
        $this->resetEvents();
        $path = $this->filePath();
        $s = $this->service();
        $companyId = rand(1000, 1000000);

        $rpf = $s->saveRoyaltyPaymentFile($path, $companyId, ["year" => 2015, "month" => 3]);
        $s->savePayments($rpf);
        $s->savePayees($rpf);

        $payees = User::all();
        $this->assertTrue(count($payees) == 2);
        $payee1 = $payees[0];
        $this->assertTrue($payee1->id == 1);
        $this->assertTrue($payee1->company_id == $companyId);
        $this->assertTrue($payee1->type == "payee");
        $this->assertTrue($payee1->email == null);
        $this->assertTrue($payee1->code == "1175");
        $this->assertTrue($payee1->name == "Studio 51");
        $payee2 = $payees[1];
        $this->assertTrue($payee2->id == 2);
        $this->assertTrue($payee2->company_id == $companyId);
        $this->assertTrue($payee2->type == "payee");
        $this->assertTrue($payee2->email == null);
        $this->assertTrue($payee2->code == "1379");
        $this->assertTrue($payee2->name == "Pop Virus (PACIFICA)");

        $s->savePayeePayments($rpf, ["year" => 2015, "month" => 3]);

        $payeePayments = PayeePayment::all();
        $this->assertTrue(count($payeePayments) == 2);

        $this->assertTrue($payeePayments[0]->payment_date == null);
        $this->assertTrue($payeePayments[0]->status == "unpaid");
        $this->assertTrue($payeePayments[0]->year == 2015);
        $this->assertTrue($payeePayments[0]->month == 3);
        $this->assertTrue($payeePayments[0]->quarter == null);
        $this->assertTrue($payeePayments[1]->year == 2015);
        $this->assertTrue($payeePayments[1]->month == 3);
        $this->assertTrue($payeePayments[1]->quarter == null);

        $paymentsSum = RoyaltyPayment::where("payee_payment_id", "=", $payeePayments[0]->id)->select(DB::raw('sum(amount_received) AS amount'))->get()[0];
        $this->assertTrue($paymentsSum->amount == $payeePayments[0]->amount);

        $paymentsSum = RoyaltyPayment::where("payee_payment_id", "=", $payeePayments[1]->id)->select(DB::raw('sum(amount_received) AS amount'))->get()[0];
        $this->assertTrue($paymentsSum->amount == $payeePayments[1]->amount);

        $this->resetEvents();
    }

    public function testProcessVerifyConvertToStreams()
    {
        $this->resetEvents();
        $path = $this->copyFileToTemp($this->fileName());
        $s = $this->service();
        $companyId = rand(1000, 1000000);
        $fileDetails = ["year" => 2015,
                        "quarter" => 2];

        $rpf = $s->process($path, $companyId, $fileDetails);
        $this->assertTrue($rpf->status == RoyaltyPaymentFile::STATUS_PAYMENTS_PROCESSED);

        $rpf = $s->processRoyaltyStreams($rpf);
        $this->assertTrue($rpf->status == RoyaltyPaymentFile::STATUS_PROCESSED);

        $payees = User::all();
        $payee1 = $payees[0];
        $payee2 = $payees[1];

        //deal care are payee_code = user payee_code
        $deals = Deal::all();
        $this->assertTrue(count($deals) == 2);
        $this->assertTrue($deals[0]->name == "Statement Analysis for ".$payee1->name);
        $this->assertTrue($deals[0]->etl_status == "processed");
        $this->assertTrue($deals[0]->company_id == $payee1->company_id);
        $this->assertTrue($deals[0]->payee_code == $payee1->code);
        $this->assertTrue($deals[0]->payment_analysis == 1);

        $this->assertTrue($deals[1]->name == "Statement Analysis for ".$payee2->name);
        $this->assertTrue($deals[1]->etl_status == "processed");
        $this->assertTrue($deals[1]->company_id == $payee2->company_id);
        $this->assertTrue($deals[1]->payee_code == $payee2->code);
        $this->assertTrue($deals[1]->payment_analysis == 1);

        $rsfs = RoyaltyStreamFile::all();
        $this->assertTrue(count($rsfs) == 2);
        $rsf1 = RoyaltyStreamFile::where("deal_id", "=", $deals[0]->id)->first();
        $this->assertTrue($rsf1 != null);
        $this->assertTrue($rsf1->stream_file_name == $rpf->path);
        $this->assertTrue($rsf1->period_year == $fileDetails["year"]);
        $this->assertTrue($rsf1->period_quarter == $fileDetails["quarter"]);
        $this->assertTrue($rsf1->period_month == 4);
        $this->assertTrue($rsf1->company_id == $payee1->company_id);
        $this->assertTrue($rsf1->period_type == 1);
        $this->assertTrue($rsf1->period_year_quarter == $fileDetails["year"]."Q".$fileDetails["quarter"]);
        $rsf2 = RoyaltyStreamFile::where("deal_id", "=", $deals[1]->id)->first();
        $this->assertTrue($rsf2 != null);
        $this->assertTrue($rsf2->stream_file_name == $rpf->path);
        $this->assertTrue($rsf2->period_year == $fileDetails["year"]);
        $this->assertTrue($rsf2->period_quarter == $fileDetails["quarter"]);
        $this->assertTrue($rsf2->period_month == 4);
        $this->assertTrue($rsf2->company_id == $payee2->company_id);
        $this->assertTrue($rsf2->period_type == 1);
        $this->assertTrue($rsf2->period_year_quarter == $fileDetails["year"]."Q".$fileDetails["quarter"]);


        $streams = RoyaltyStream::all();
        $this->assertTrue(count($streams) == 4);

        $this->assertTrue($streams[0]->royalty_country_iso == "US");
        $this->assertTrue($streams[0]->royalty_currency == "USD");
        $this->assertTrue($streams[0]->song_number == "60106");
        $this->assertTrue($streams[0]->song_title == "10 On Top - CUES");
        $this->assertTrue($streams[0]->royalty_amount == 2.71);
        $this->assertTrue($streams[0]->party_name == "Coon");
        $this->assertTrue($streams[0]->performance_source == "ASCAP");
        $this->assertTrue($streams[0]->serial_or_film == "Program Name: ONE DIRECTION ROBIN THICKE AND THE VAMPI");
        $this->assertTrue($streams[0]->region == "US");
        $this->assertTrue($streams[0]->number_of_plays == 0);
        $this->assertTrue($streams[0]->statement_period_from == "2014-01-00 00:00:00");
        $this->assertTrue($streams[0]->statement_period_to == "2014-03-00 00:00:00");
        $this->assertTrue($streams[0]->episode_name == "");
        $this->assertTrue($streams[0]->period_year == $fileDetails["year"]);
        $this->assertTrue($streams[0]->period_month == 4);
        $this->assertTrue($streams[0]->period_quarter == $fileDetails["quarter"]);
        $this->assertTrue($streams[0]->participant_percent == 100);
        $this->assertTrue($streams[0]->performance_year == 2014);
        $this->assertTrue($streams[0]->performance_month == 1);
        $this->assertTrue($streams[0]->performance_quarter == 1);
        $this->assertTrue($streams[0]->royalty_type == "Performance");
        $this->assertTrue($streams[0]->account_name == "Pop Virus (PACIFICA)");
        $this->assertTrue($streams[0]->company_name == "Pacifica Music Library");
        $this->assertTrue($streams[0]->company_code == "PAC1");
        $this->assertTrue($streams[0]->payee_name == "Pop Virus (PACIFICA)");
        $this->assertTrue($streams[0]->payee_code == "1379");
        $this->assertTrue($streams[0]->stream_file_id == $rsf2->id);

        $this->assertTrue($streams[1]->royalty_country_iso == "CA");
        $this->assertTrue($streams[1]->royalty_currency == "USD");
        $this->assertTrue($streams[1]->song_number == "77674");
        $this->assertTrue($streams[1]->song_title == "10 On Top Cues");
        $this->assertTrue($streams[1]->royalty_amount == 0.09);
        $this->assertTrue($streams[1]->party_name == "Coon/Helmich");
        $this->assertTrue($streams[1]->performance_source == "ASCAP Intl");
        $this->assertTrue($streams[1]->serial_or_film == "");
        $this->assertTrue($streams[1]->region == "CA");
        $this->assertTrue($streams[1]->number_of_plays == 0);
        $this->assertTrue($streams[1]->statement_period_from == "2012-10-00 00:00:00");
        $this->assertTrue($streams[1]->statement_period_to == "2012-12-00 00:00:00");
        $this->assertTrue($streams[1]->episode_name == "");
        $this->assertTrue($streams[1]->period_year == $fileDetails["year"]);
        $this->assertTrue($streams[1]->period_month == 4);
        $this->assertTrue($streams[1]->period_quarter == $fileDetails["quarter"]);
        $this->assertTrue($streams[1]->participant_percent == 100);
        $this->assertTrue($streams[1]->performance_year == 2012);
        $this->assertTrue($streams[1]->performance_month == 10);
        $this->assertTrue($streams[1]->performance_quarter == 4);
        $this->assertTrue($streams[1]->royalty_type == "Performance");
        $this->assertTrue($streams[1]->account_name == "Pop Virus (PACIFICA)");
        $this->assertTrue($streams[1]->company_name == "Pacifica Music Library");
        $this->assertTrue($streams[1]->company_code == "PAC1");
        $this->assertTrue($streams[1]->payee_name == "Pop Virus (PACIFICA)");
        $this->assertTrue($streams[1]->payee_code == "1379");
        $this->assertTrue($streams[1]->stream_file_id == $rsf2->id);

        $this->assertTrue($streams[2]->royalty_country_iso == "DE");
        $this->assertTrue($streams[2]->royalty_currency == "USD");
        $this->assertTrue($streams[2]->song_number == "45297");
        $this->assertTrue($streams[2]->song_title == "2nd Hand Ring");
        $this->assertTrue($streams[2]->royalty_amount == 23.7);
        $this->assertTrue($streams[2]->party_name == "Keen");
        $this->assertTrue($streams[2]->performance_source == "GEMA");
        $this->assertTrue($streams[2]->serial_or_film == "Source Statement Id: 2137");
        $this->assertTrue($streams[2]->region == "DE");
        $this->assertTrue($streams[2]->number_of_plays == 0);
        $this->assertTrue($streams[2]->statement_period_from == "2012-01-00 00:00:00");
        $this->assertTrue($streams[2]->statement_period_to == "2012-12-00 00:00:00");
        $this->assertTrue($streams[2]->episode_name == "");
        $this->assertTrue($streams[2]->period_year == $fileDetails["year"]);
        $this->assertTrue($streams[2]->period_month == 4);
        $this->assertTrue($streams[2]->period_quarter == $fileDetails["quarter"]);
        $this->assertTrue($streams[2]->participant_percent == 100);
        $this->assertTrue($streams[2]->performance_year == 2012);
        $this->assertTrue($streams[2]->performance_month == 1);
        $this->assertTrue($streams[2]->performance_quarter == 1);
        $this->assertTrue($streams[2]->royalty_type == "Performance");
        $this->assertTrue($streams[2]->account_name == "Studio 51");
        $this->assertTrue($streams[2]->company_name == "Pacifica Music Library");
        $this->assertTrue($streams[2]->company_code == "PAC1");
        $this->assertTrue($streams[2]->payee_name == "Studio 51");
        $this->assertTrue($streams[2]->payee_code == "1175");
        $this->assertTrue($streams[2]->stream_file_id == $rsf1->id);

        $this->assertTrue($streams[3]->royalty_country_iso == "DE");
        $this->assertTrue($streams[3]->royalty_currency == "USD");
        $this->assertTrue($streams[3]->song_number == "45297");
        $this->assertTrue($streams[3]->song_title == "2nd Hand Ring");
        $this->assertTrue($streams[3]->royalty_amount == 22.4);
        $this->assertTrue($streams[3]->party_name == "Keen");
        $this->assertTrue($streams[3]->performance_source == "GEMA");
        $this->assertTrue($streams[3]->serial_or_film == "Source Statement Id: 2137");
        $this->assertTrue($streams[3]->region == "DE");
        $this->assertTrue($streams[3]->number_of_plays == 0);
        $this->assertTrue($streams[3]->statement_period_from == "2012-01-00 00:00:00");
        $this->assertTrue($streams[3]->statement_period_to == "2012-12-00 00:00:00");
        $this->assertTrue($streams[3]->episode_name == "");
        $this->assertTrue($streams[3]->period_year == $fileDetails["year"]);
        $this->assertTrue($streams[3]->period_month == 4);
        $this->assertTrue($streams[3]->period_quarter == $fileDetails["quarter"]);
        $this->assertTrue($streams[3]->participant_percent == 100);
        $this->assertTrue($streams[3]->performance_year == 2012);
        $this->assertTrue($streams[3]->performance_month == 1);
        $this->assertTrue($streams[3]->performance_quarter == 1);
        $this->assertTrue($streams[3]->royalty_type == "Mechanical");
        $this->assertTrue($streams[3]->account_name == "Studio 51");
        $this->assertTrue($streams[3]->company_name == "Pacifica Music Library");
        $this->assertTrue($streams[3]->company_code == "PAC1");
        $this->assertTrue($streams[3]->payee_name == "Studio 51");
        $this->assertTrue($streams[3]->payee_code == "1175");
        $this->assertTrue($streams[3]->stream_file_id == $rsf1->id);

        $this->resetEvents();
    }

    public function testProcessVerifyConvertToStreamsForMonths()
    {
        $this->resetEvents();
        $path = $this->copyFileToTemp($this->fileName());
        $s = $this->service();
        $companyId = rand(1000, 1000000);
        $fileDetails = ["year" => 2015,
                        "month" => 7];

        $rpf = $s->process($path, $companyId, $fileDetails);
        $this->assertTrue($rpf->status == RoyaltyPaymentFile::STATUS_PAYMENTS_PROCESSED);

        $rpf = $s->processRoyaltyStreams($rpf);
        $this->assertTrue($rpf->status == RoyaltyPaymentFile::STATUS_PROCESSED);

        $streams = RoyaltyStream::all();
        $this->assertTrue(count($streams) == 4);

        $this->assertTrue($streams[0]->royalty_country_iso == "US");
        $this->assertTrue($streams[0]->royalty_currency == "USD");
        $this->assertTrue($streams[0]->song_number == "60106");
        $this->assertTrue($streams[0]->song_title == "10 On Top - CUES");
        $this->assertTrue($streams[0]->royalty_amount == 2.71);
        $this->assertTrue($streams[0]->party_name == "Coon");
        $this->assertTrue($streams[0]->performance_source == "ASCAP");
        $this->assertTrue($streams[0]->serial_or_film == "Program Name: ONE DIRECTION ROBIN THICKE AND THE VAMPI");
        $this->assertTrue($streams[0]->region == "US");
        $this->assertTrue($streams[0]->number_of_plays == 0);
        $this->assertTrue($streams[0]->statement_period_from == "2014-01-00 00:00:00");
        $this->assertTrue($streams[0]->statement_period_to == "2014-03-00 00:00:00");
        $this->assertTrue($streams[0]->episode_name == "");
        $this->assertTrue($streams[0]->period_year == $fileDetails["year"]);
        $this->assertTrue($streams[0]->period_month == $fileDetails["month"]);
        $this->assertTrue($streams[0]->period_quarter == 3);
        $this->assertTrue($streams[0]->participant_percent == 100);
        $this->assertTrue($streams[0]->performance_year == 2014);
        $this->assertTrue($streams[0]->performance_month == 1);
        $this->assertTrue($streams[0]->performance_quarter == 1);
        $this->assertTrue($streams[0]->royalty_type == "Performance");
        $this->assertTrue($streams[0]->account_name == "Pop Virus (PACIFICA)");
        $this->assertTrue($streams[0]->company_name == "Pacifica Music Library");
        $this->assertTrue($streams[0]->company_code == "PAC1");
        $this->assertTrue($streams[0]->payee_name == "Pop Virus (PACIFICA)");
        $this->assertTrue($streams[0]->payee_code == "1379");

        $rollupEvents = RollupEvent::all();
        $this->assertTrue(count($rollupEvents) > 0);

        $this->resetEvents();
    }

    public function testProcessVerifyConvertToStreamsLarge()
    {
        $this->resetEvents();
        $fileName = "test_royalty_payment_file_large.csv";
        $path = $this->copyFileToTemp($fileName);
        $s = $this->service();
        $companyId = rand(1000, 1000000);

        $fileDetails = ["year" => 2015,
                        "month" => 7];

        $rpf = $s->process($path, $companyId, $fileDetails);
        $this->assertTrue($rpf->status == RoyaltyPaymentFile::STATUS_PAYMENTS_PROCESSED);

        $rpf = $s->processRoyaltyStreams($rpf);
        $this->assertTrue($rpf->status == RoyaltyPaymentFile::STATUS_PROCESSED);

        $streams = RoyaltyStream::all();
        $this->assertTrue(count($streams) == 5434);
        $streamFiles = RoyaltyStreamFile::all();
        $this->assertTrue(count($streamFiles) == 41);

        $this->resetEvents();
    }

    public function testVerifyClients()
    {
        $this->resetEvents();
        $path = $this->copyFileToTemp($this->fileName());
        $s = $this->service();
        $companyId = rand(1000, 1000000);
        $fileDetails = ["year" => 2015,
                        "month" => 7];

        $rpf = $s->process($path, $companyId, $fileDetails);
        $this->assertTrue($rpf->status == RoyaltyPaymentFile::STATUS_PAYMENTS_PROCESSED);

        $rpf = $s->processRoyaltyStreams($rpf);
        $this->assertTrue($rpf->status == RoyaltyPaymentFile::STATUS_PROCESSED);

        $clients = Client::all();
        $this->assertTrue(count($clients) == 2);
        $this->assertTrue($clients[1]->name == "Pop Virus (PACIFICA)");
        $this->assertTrue($clients[1]->code == "1379");
        $this->assertTrue($clients[0]->name =="Studio 51");
        $this->assertTrue($clients[0]->code == "1175");

        $payeePayments = PayeePayment::all();
        $this->assertTrue(count($payeePayments) == 2);

        $this->assertTrue($payeePayments[1]->client_code == $clients[1]->code);
        $this->assertTrue($payeePayments[0]->client_code == $clients[0]->code);

        $this->resetEvents();
    }

    public function testEmptyLinesInFile()
    {
        $this->resetEvents();
        $fileName = "with_empty_lines.csv";
        $path = $this->copyFileToTemp($fileName);
        $s = $this->service();
        $companyId = rand(1000, 1000000);

        $fileDetails = ["year" => 2015,
                        "month" => 7];

        $rpf = $s->process($path, $companyId, $fileDetails);
        $this->assertTrue($rpf->status == RoyaltyPaymentFile::STATUS_PAYMENTS_PROCESSED);

        $rpf = $s->processRoyaltyStreams($rpf);
        $this->assertTrue($rpf->status == RoyaltyPaymentFile::STATUS_PROCESSED);

        $payments = RoyaltyPayment::all();
        $this->assertTrue(count($payments) == 4);
        $this->assertTrue($payments[0]->payee_code == 1128);
        $this->assertTrue($payments[1]->payee_code == 1111);
        $this->assertTrue($payments[2]->payee_code == 1545);
        $this->assertTrue($payments[3]->payee_code == 1332);

        $clients = Client::all();
        $this->assertTrue(count($clients) == 4);

        $payeePayments = PayeePayment::all();
        $this->assertTrue(count($payments) == 4);

        $deals = Deal::all();
        $this->assertTrue(count($deals) == 4);

        $streams = RoyaltyStream::all();
        $this->assertTrue(count($streams) == 4);
        $this->assertTrue($streams[0]->song_number == 33959);
        $this->assertTrue($streams[1]->song_number == 14402);
        $this->assertTrue($streams[2]->song_number == 12043);
        $this->assertTrue($streams[3]->song_number == 51311);

        $streamFiles = RoyaltyStreamFile::all();
        $this->assertTrue(count($streamFiles) == 4);

        $this->resetEvents();
    }

    public function testMultipleCompaniesForTheSamePayees()
    {
        $this->resetEvents();
        $path = $this->copyFileToTemp($this->fileName());
        $s = $this->service();
        $companyId1 = rand(1000, 1000000);
        $companyId2 = rand(1000, 1000000);

        $fileDetails = ["year" => 2015,
                        "month" => 7];

        $rpf = $s->process($path, $companyId1, $fileDetails);
        $this->assertTrue($rpf->status == RoyaltyPaymentFile::STATUS_PAYMENTS_PROCESSED);

        $path = $this->copyFileToTemp($this->fileName());

        $rpf = $s->process($path, $companyId2, $fileDetails);
        $this->assertTrue($rpf->status == RoyaltyPaymentFile::STATUS_PAYMENTS_PROCESSED);

        $payees = User::all();
        $this->assertTrue(count($payees) == 2);
        $payee1 = $payees[0];
        $this->assertTrue($payee1->id == 1);
        $this->assertTrue($payee1->company_id == $companyId1);
        $this->assertTrue($payee1->type == "payee");
        $this->assertTrue($payee1->email == null);
        $this->assertTrue($payee1->code == "1175");
        $this->assertTrue($payee1->name == "Studio 51");
        $payee2 = $payees[1];
        $this->assertTrue($payee2->id == 2);
        $this->assertTrue($payee2->company_id == $companyId1);
        $this->assertTrue($payee2->type == "payee");
        $this->assertTrue($payee2->email == null);
        $this->assertTrue($payee2->code == "1379");
        $this->assertTrue($payee2->name == "Pop Virus (PACIFICA)");

        $payeeCompany = PayeeCompany::all();
        $this->assertTrue(count($payeeCompany) == 4);
        $this->assertTrue($payeeCompany[0]->user_id == 1);
        $this->assertTrue($payeeCompany[0]->code == 1175);
        $this->assertTrue($payeeCompany[0]->company_id == $companyId1);
        $this->assertTrue($payeeCompany[1]->user_id == 2);
        $this->assertTrue($payeeCompany[1]->code == 1379);
        $this->assertTrue($payeeCompany[1]->company_id == $companyId1);
        $this->assertTrue($payeeCompany[2]->user_id == 1);
        $this->assertTrue($payeeCompany[2]->code == 1175);
        $this->assertTrue($payeeCompany[2]->company_id == $companyId2);
        $this->assertTrue($payeeCompany[3]->user_id == 2);
        $this->assertTrue($payeeCompany[3]->code == 1379);
        $this->assertTrue($payeeCompany[3]->company_id == $companyId2);

        $path = $this->copyFileToTemp($this->fileName());

        $rpf = $s->process($path, $companyId2, $fileDetails);
        $this->assertTrue($rpf->status == RoyaltyPaymentFile::STATUS_PAYMENTS_PROCESSED);

        $payeeCompany = PayeeCompany::all();
        $this->assertTrue(count($payeeCompany) == 4);
        $this->assertTrue($payeeCompany[0]->user_id == 1);
        $this->assertTrue($payeeCompany[0]->code == 1175);
        $this->assertTrue($payeeCompany[0]->company_id == $companyId1);
        $this->assertTrue($payeeCompany[1]->user_id == 2);
        $this->assertTrue($payeeCompany[1]->code == 1379);
        $this->assertTrue($payeeCompany[1]->company_id == $companyId1);
        $this->assertTrue($payeeCompany[2]->user_id == 1);
        $this->assertTrue($payeeCompany[2]->code == 1175);
        $this->assertTrue($payeeCompany[2]->company_id == $companyId2);
        $this->assertTrue($payeeCompany[3]->user_id == 2);
        $this->assertTrue($payeeCompany[3]->code == 1379);
        $this->assertTrue($payeeCompany[3]->company_id == $companyId2);

        $this->resetEvents();
    }
}