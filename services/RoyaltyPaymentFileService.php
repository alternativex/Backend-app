<?php

set_time_limit(0);

class RoyaltyPaymentFileService
{
    public function process($filePath, $companyId, $fileDetails)
    {
        DB::beginTransaction();
        try {
            $rpf = $this->saveFile($filePath, $companyId, $fileDetails);
            $this->savePayments($rpf);
            $this->savePayees($rpf);
            $this->saveClients($rpf);
            $this->savePayeePayments($rpf);
            $rpf->status = RoyaltyPaymentFile::STATUS_PAYMENTS_PROCESSED;
            $rpf->save();
            DB::commit();
            return $rpf;
        } catch(Exception $ex) {
            DB::rollback();
            throw $ex;
        }
    }

    public function processRoyaltyStreams($rpf)
    {
        DB::beginTransaction();
        try {
            $this->convertToRoyaltyStreams($rpf);
            $this->runRollups();
            $rpf->status = RoyaltyPaymentFile::STATUS_PROCESSED;
            $rpf->save();
            unlink($rpf->path);
            DB::commit();
            return $rpf;
        } catch(Exception $ex) {
            DB::rollback();
            throw $ex;
        }
    }

    public function convertToRoyaltyStreams($rpf)
    {
        $payments = RoyaltyPayment::attachedGroupedByPayeeCode($rpf->id)->get();
        $royaltyStreamFiles = [];
        $rollupDeals = [];
        foreach ($payments as $payment) {
            $user = User::where('code', '=', $payment->payee_code)->first();
            if ($user != null) {
                $deal = $this->findOrCreateDeal($user);
                $rollupDeals[] = $deal->id;
                $royaltyStreamFile = $this->saveRoyaltyStreamFile($rpf, $deal);
                $royaltyStreamFiles[] = $royaltyStreamFile;
            }
        }
        RoyaltyStream::loadFromFile($royaltyStreamFiles);
        RoyaltyStream::cleanEmptyStreams();
        $this->runRollupDeals($rollupDeals);
    }

    public function runRollupDeals($dealIds)
    {
        foreach ($dealIds as $dealId)
            RollupEvent::EXECUTE_DEAL_ROLLUPS($dealId);
    }

    public function runRollups()
    {
        RollupEvent::EXECUTE_OVERALL_ROLLUPS();
    }

    public function saveRoyaltyStreamFile($rpf, $deal)
    {
        $periodType = !empty($rpf->quarter) ? 1 : 0;
        $quarter = !empty($rpf->quarter) ? $rpf->quarter : ceil($rpf->month/3);
        $month = !empty($rpf->month) ? $rpf->month : $quarter*3-2;

        $rsf = RoyaltyStreamFile::create(["stream_file_name"    => $rpf->path,
                                          "period_year"         => $rpf->year,
                                          "period_quarter"      => $quarter,
                                          "period_month"        => $month,
                                          "period_type"         => $periodType,
                                          "company_id"          => $deal->company_id,
                                          "deal_id"             => $deal->id,
                                          "period_year_quarter" => $rpf->year."Q".$quarter]);
        return $rsf;
    }

    public function findOrCreateDeal($user)
    {
        $deal = Deal::where("payee_code", "=", $user->code)->where("payment_analysis", "=", 1)->first();
        if ($deal == null)
            $deal = Deal::create(["name"             => "Statement Analysis for ".$user->name,
                                  "payee_code"       => $user->code,
                                  "etl_status"       => "processed",
                                  "payment_analysis" => 1,
                                  "company_id"       => $user->company_id]);
        return $deal;
    }

    public function savePayees($rpf)
    {
//        $payments = RoyaltyPayment::groupedByPayeeCode($rpf->id)->get();
//        foreach ($payments as $payment) {
//            $user = User::where('code', '=', $payment->payee_code)->first();
//            if ($user == null) {
//                $user = User::create(["name"       => $payment->payee_name,
//                                      "deleted_at" => null,
//                                      "email"      => null,
//                                      "code"       => $payment->payee_code,
//                                      "company_id" => $rpf->company_id,
//                                      "password"   => $payment->payee_code,
//                                      "type"       => "payee"]);
//                PayeeCompany::create(["user_id" => $user->id, "code" => $user->code, "company_id" => $rpf->company_id]);
//            } else {
//                if (PayeeCompany::findByUserAndCompany($user->id, $rpf->company_id) == null)
//                    PayeeCompany::create(["user_id" => $user->id, "code" => $user->code, "company_id" => $rpf->company_id]);
//            }
//        }
        DB::connection()->getpdo()->exec(
            'INSERT IGNORE INTO '.User::table().
            '(`name`, `deleted_at`, `email`, `code`, `company_id`, `password`, `type`)
             SELECT `payee_name`, NULL, NULL, `payee_code`, '.$rpf->company_id.', ENCRYPT(`payee_code`), \'payee\'
              FROM '.RoyaltyPayment::table().'
              WHERE `royalty_payment_file_id` = '.$rpf->id.' AND `payee_payment_id` IS NULL
              GROUP BY `payee_code` ORDER BY `payee_code`');

        DB::connection()->getpdo()->exec(
            'INSERT IGNORE INTO '.PayeeCompany::table().
            '(`user_id`, `code`, `company_id`)
             SELECT u.id, `payee_code`, '.$rpf->company_id.'
              FROM '.RoyaltyPayment::table().' as rp
              LEFT JOIN '.User::table().' as u on rp.payee_code = u.code
              WHERE `royalty_payment_file_id` = '.$rpf->id.' AND `payee_payment_id` IS NULL
              GROUP BY `payee_code` ORDER BY `payee_code`');
    }

    public function savePayeePayments($rpf)
    {
//        $paymentSums = RoyaltyPayment::sumAmountReceivedGroupedByPayeeCode($rpf->id)->get();
//        foreach ($paymentSums as $payeePaymentSum) {
//            $payeePayment = PayeePayment::create(["amount"      => $payeePaymentSum->amount,
//                                                  "status"      => "unpaid",
//                                                  "payee_code"  => $payeePaymentSum->payee_code,
//                                                  "company_id"  => $rpf->company_id,
//                                                  "client_code" => $payeePaymentSum->client_code,
//                                                  "year"        => $rpf->year,
//                                                  "quarter"     => $rpf->quarter,
//                                                  "month"       => $rpf->month,
//                                                  "half_year"       => $rpf->half_year]);
//            RoyaltyPayment::unattachedPaymentsForFileAndPayee($rpf->id, $payeePaymentSum->payee_code)
//                ->update(["payee_payment_id" => $payeePayment->id]);
//        }

        $year = empty($rpf->year) ? 'NULL' : $rpf->year;
        $quarter = empty($rpf->quarter) ? 'NULL' : $rpf->quarter;
        $month = empty($rpf->month) ? 'NULL' : $rpf->month;
        $halfYear = empty($rpf->half_year) ? 'NULL' : $rpf->half_year;

        DB::connection()->getpdo()->exec(
            'INSERT INTO '.PayeePayment::table().
            '(`amount`, `status`, `payee_code`, `company_id`, `client_code`, `year`, `quarter`, `month`, `half_year`)
             SELECT sum(`amount_received`) AS amount, \'unpaid\', `payee_code`, '.$rpf->company_id.', `client_code`, '.$year.', '.$quarter.', '.$month.', '.$halfYear.'
              FROM '.RoyaltyPayment::table().'
              WHERE `royalty_payment_file_id` = '.$rpf->id.' AND `payee_payment_id` IS NULL
              GROUP BY `payee_code` ORDER BY `payee_code`');

        DB::connection()->getpdo()->exec(
            'UPDATE ' . RoyaltyPayment::table() . ' as rp ' .
            'LEFT JOIN ' . PayeePayment::table() . ' as pp ' .
            'ON rp.payee_code = pp.payee_code ' .
            'SET rp.payee_payment_id = pp.id ' .
            'WHERE rp.royalty_payment_file_id = '.$rpf->id);
    }

    public function saveClients($rpf)
    {
//        $payments = RoyaltyPayment::groupedByClientCode($rpf->id)->get();
//        foreach ($payments as $payment)
//            $client = Client::create(["name" => $payment->client_name, "code" => $payment->client_code]);
        DB::connection()->getpdo()->exec(
            'INSERT IGNORE INTO '.Client::table().
            '(`name`, `code`)
             SELECT `client_name`, `client_code`
              FROM '.RoyaltyPayment::table().'
              WHERE `royalty_payment_file_id` = '.$rpf->id.' AND `payee_payment_id` IS NULL
              GROUP BY `client_code` ORDER BY `client_code`');
    }

    public function savePayments($rpf)
    {
        RoyaltyPayment::loadFromFile($rpf);
        RoyaltyPayment::cleanEmptyPayments();
    }

    public function saveFile($filePath, $companyId, $fileDetails)
    {
        $rpf = $this->saveRoyaltyPaymentFile($filePath, $companyId, $fileDetails);
        return $rpf;
    }

    public function saveRoyaltyPaymentFile($filePath, $companyId, $fileDetails)
    {
        $pathInfo = pathinfo($filePath);
        return RoyaltyPaymentFile::create(["name"       => $pathInfo["basename"],
                                           "path"       => $filePath,
                                           "status"     => RoyaltyPaymentFile::STATUS_UPLOADED,
                                           "company_id" => $companyId,
                                           "year"       => $fileDetails["year"],
                                           "quarter"    => isset($fileDetails["quarter"]) ? $fileDetails["quarter"] : null,
                                           "month"      => isset($fileDetails["month"]) ? $fileDetails["month"] : null,
                                           "half_year"      => isset($fileDetails["halfYear"]) ? $fileDetails["halfYear"] : null]);
    }

    public function downloadHeadersFile()
    {
        return [
            "content_type" => "text/csv",
            "filename" => "royalty_payment_file_headers.csv",
            "content" => file_get_contents(public_path('files')."/royalty_payment_file_headers.csv")
        ];
    }
}