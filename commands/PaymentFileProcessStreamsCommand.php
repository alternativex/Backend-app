<?php

use GrahamCampbell\Dropbox\DropboxManager;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class PaymentFileProcessStreamsCommand extends LockedConcurrentCommand {

    protected $name = 'payment-file:process-streams';

    protected $description = 'Processing streams for payment file';

    public function fireLocked()
    {
        try {
            $rpfs = RoyaltyPaymentFile::paymentProcessed()->get();
            $this->log('found '.count($rpfs).' payment files');
            $service = App::make("RoyaltyPaymentFileService");
            foreach ($rpfs as $rpf) {
                $this->log('start processing '.$rpf->id);
                $service->processRoyaltyStreams($rpf);
                $this->log('end processing '.$rpf->id);
            }
        } catch(Exception $e) {
            $this->log('something bad happened: ' . get_class($e) . ' - ' . $e->getMessage()." FILE ".$e->getFile()." LINE ".$e->getLine());
        }
    }
}
