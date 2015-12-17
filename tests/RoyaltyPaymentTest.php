<?php

class RoyaltyPaymentTest extends TestCase
{
    protected function modelsToReset()
    {
        return ["RoyaltyPaymentFile", "RoyaltyPayment"];
    }

    private function filePath()
    {
        return dirname(__FILE__)."/files/".$this->fileName();
    }

    private function fileName()
    {
        return "test_royalty_payment_file.csv";
    }

    public function testLoadFromFile()
    {
        $this->resetEvents();
        $companyId = 1;
        $rpf = RoyaltyPaymentFile::create(["name" => $this->fileName(), "path" => $this->filePath(), "company_id" => $companyId]);

        RoyaltyPayment::loadFromFile($rpf);

        $payments = RoyaltyPayment::all();
        $this->assertTrue(count($payments) == 4);
        $payment = $payments[0];
        $this->assertTrue($payment->id == 1);
        $this->assertTrue($payment->company_id == $companyId);
        $this->assertTrue($payment->royalty_payment_file_id == $rpf->id);
        $this->assertTrue($payment->payee_name == "Pop Virus (PACIFICA)");
        $this->assertTrue($payment->payee_code == 1379);
        $this->assertTrue($payment->client_name == "Pop Virus (PACIFICA)");
        $this->assertTrue($payment->client_code == 1379);
        $this->assertTrue($payment->song_title == "10 On Top - CUES");
        $this->assertTrue($payment->song_code == 60106);
        $this->assertTrue($payment->composers == "Coon");
        $this->assertTrue($payment->source_name == "ASCAP");
        $this->assertTrue($payment->source_code == "ASCA");
        $this->assertTrue($payment->income_type_description == "Performance");
        $this->assertTrue($payment->income_type == 2);
        $this->assertTrue($payment->percent_received == 50);
        $this->assertTrue($payment->amount_received == 5.41);
        $this->assertTrue($payment->share == 100);
        $this->assertTrue($payment->contractual_rate == 50);
        $this->assertTrue($payment->contractual_code == 1379);
        $this->assertTrue($payment->effective_rate == 50);
        $this->assertTrue($payment->amount_earned == 2.71);
        $this->assertTrue($payment->catalogue_number == "BG");
        $this->assertTrue($payment->units == 0);
        $this->assertTrue($payment->price == 0);
        $this->assertTrue($payment->date_period_from == "2014-01-00");
        $this->assertTrue($payment->date_period_to == "2014-03-00");
        $this->assertTrue($payment->territory_name == "USA");
        $this->assertTrue($payment->territory_code == "USA");
        $this->assertTrue($payment->production_episode == '');
        $this->assertTrue($payment->production_episode_code == 0);
        $this->assertTrue($payment->notes == "Program Name: ONE DIRECTION ROBIN THICKE AND THE VAMPI");
        $this->assertTrue($payment->currency == "USD");
        $this->assertTrue($payment->statement_id == 5620);
        $this->assertTrue($payment->statement_line == 68);

        $this->resetEvents();
    }
}