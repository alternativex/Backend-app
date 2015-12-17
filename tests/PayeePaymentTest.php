<?php

class PayeePaymentTest extends TestCase {

    protected function modelsToReset()
    {
        return ["PayeePayment", "RoyaltyPayment", "Advance", "User", "AdvancePayment"];
    }

    private static function userArray() {
        return ["code" => "1234", "email" => "1234@test.com", "company_id" => 1, "type" => "payee"];
    }

    private static function advanceArray() {
        return ["payee_code" => "1234", "amount" => 1000.00, "start_date" => date("Y-m-d"), "status" => "incomplete"];
    }

    private static function payeePaymentArray() {
        return ["payee_code" => "1234", "amount" => 150.00, "status" => "unpaid", "company_id" => 1];
    }

    private static function royaltyPaymentsArray($payeePayment)
    {
        return ["payee_code" => "1234", "company_id" => 1, "payee_payment_id" => $payeePayment->id, "payee_code" => $payeePayment->payee_code, "amount_received" => 50.00];
    }

    public function testMarkAsPaidWithAdvance()
    {
        $this->resetEvents();
        $user = User::create(self::userArray());
        $advance = Advance::create(self::advanceArray());
        $paymentDate = date("Y-m-d", strtotime("+ 5 days"));
        $payeePayment = PayeePayment::create(self::payeePaymentArray());
        RoyaltyPayment::create(self::royaltyPaymentsArray($payeePayment));
        RoyaltyPayment::create(self::royaltyPaymentsArray($payeePayment));
        RoyaltyPayment::create(self::royaltyPaymentsArray($payeePayment));

        $payeePayment->markAsPaid($paymentDate);

        $advance1 = Advance::find($advance->id);
        $this->assertTrue($advance1->status == "incomplete");

        $this->assertTrue(count(AdvancePayment::all()) == 3);
        $payeePayment1 = PayeePayment::find($payeePayment->id);
        $this->assertTrue($payeePayment1->status == "paid");

        $this->resetEvents();
    }

    public function testMarkAsPaidWithAdvanceCompleteFixedAmount()
    {
        $this->resetEvents();
        $user = User::create(self::userArray());
        $advance = Advance::create(self::advanceArray());
        $paymentDate = date("Y-m-d", strtotime("+ 5 days"));
        $payeePayment = PayeePayment::create(self::payeePaymentArray());
        $royaltyPayment = self::royaltyPaymentsArray($payeePayment);
        $royaltyPayment["amount_received"] = 500.00;
        RoyaltyPayment::create($royaltyPayment);
        RoyaltyPayment::create($royaltyPayment);
        RoyaltyPayment::create($royaltyPayment);

        $payeePayment->markAsPaid($paymentDate);

        $advance1 = Advance::find($advance->id);
        $this->assertTrue($advance1->status == "complete");

        $this->assertTrue(count(AdvancePayment::all()) == 2);
        $payeePayment1 = PayeePayment::find($payeePayment->id);
        $this->assertTrue($payeePayment1->status == "paid");

        $this->resetEvents();
    }

    public function testMarkAsPaidWithAdvanceCompleteUnfixedAmount()
    {
        $this->resetEvents();
        $user = User::create(self::userArray());
        $advance = Advance::create(self::advanceArray());
        $paymentDate = date("Y-m-d", strtotime("+ 5 days"));
        $payeePayment = PayeePayment::create(self::payeePaymentArray());
        $royaltyPayment = self::royaltyPaymentsArray($payeePayment);
        $royaltyPayment["amount_received"] = 400;
        RoyaltyPayment::create($royaltyPayment);
        RoyaltyPayment::create($royaltyPayment);
        RoyaltyPayment::create($royaltyPayment);

        $payeePayment->markAsPaid($paymentDate);

        $advance1 = Advance::find($advance->id);
        $this->assertTrue($advance1->status == "complete");

        $this->assertTrue(count(AdvancePayment::all()) == 3);
        $this->assertTrue(AdvancePayment::find(3)->amount == 200);
        $payeePayment1 = PayeePayment::find($payeePayment->id);
        $this->assertTrue($payeePayment1->status == "paid");

        $this->resetEvents();
    }

    public function testMarkAsPaidWithMultipleAdvancesCompleteUnfixedAmount()
    {
        $this->resetEvents();
        $user = User::create(self::userArray());
        $advance1 = Advance::create(self::advanceArray());
        $advance2 = Advance::create(self::advanceArray());
        $paymentDate = date("Y-m-d", strtotime("+ 5 days"));
        $payeePayment = PayeePayment::create(self::payeePaymentArray());
        $royaltyPayment = self::royaltyPaymentsArray($payeePayment);
        $royaltyPayment["amount_received"] = 400;
        RoyaltyPayment::create($royaltyPayment);
        RoyaltyPayment::create($royaltyPayment);
        RoyaltyPayment::create($royaltyPayment);

        $payeePayment->markAsPaid($paymentDate);

        $this->assertTrue(count(AdvancePayment::all()) == 4);
        $this->assertTrue(count(AdvancePayment::where("advance_id", "=", $advance1->id)->get()) == 3);
        $this->assertTrue(count(AdvancePayment::where("advance_id", "=", $advance2->id)->get()) == 1);

        $advanceFound1 = Advance::find($advance1->id);
        $this->assertTrue($advanceFound1->status == "complete");

        $advanceFound2 = Advance::find($advance2->id);
        $this->assertTrue($advanceFound2->status == "incomplete");
        $this->assertTrue($advanceFound2->amountLeftToPay() == 800);

        $this->assertTrue(AdvancePayment::find(3)->amount == 200);
        $this->assertTrue(AdvancePayment::find(4)->amount == 200);

        $payeePayment1 = PayeePayment::find($payeePayment->id);
        $this->assertTrue($payeePayment1->status == "paid");

        $this->resetEvents();
    }

    public function testMarkAsPaidWithMultipleAdvances2CompleteUnfixedAmount()
    {
        $this->resetEvents();
        $user = User::create(self::userArray());
        $advance1 = Advance::create(self::advanceArray());
        $advance2 = Advance::create(self::advanceArray());
        $advance3 = Advance::create(self::advanceArray());
        $paymentDate = date("Y-m-d", strtotime("+ 5 days"));
        $payeePayment = PayeePayment::create(self::payeePaymentArray());
        $royaltyPayment = self::royaltyPaymentsArray($payeePayment);
        $royaltyPayment["amount_received"] = 800;
        RoyaltyPayment::create($royaltyPayment);
        RoyaltyPayment::create($royaltyPayment);
        RoyaltyPayment::create($royaltyPayment);

        $payeePayment->markAsPaid($paymentDate);

        $this->assertTrue(count(AdvancePayment::all()) == 5);
        $this->assertTrue(count(AdvancePayment::where("advance_id", "=", $advance1->id)->get()) == 2);
        $this->assertTrue(count(AdvancePayment::where("advance_id", "=", $advance2->id)->get()) == 2);
        $this->assertTrue(count(AdvancePayment::where("advance_id", "=", $advance3->id)->get()) == 1);

        $advanceFound1 = Advance::find($advance1->id);
        $this->assertTrue($advanceFound1->status == "complete");

        $advanceFound2 = Advance::find($advance2->id);
        $this->assertTrue($advanceFound2->status == "complete");

        $advanceFound3= Advance::find($advance3->id);
        $this->assertTrue($advanceFound3->status == "incomplete");
        $this->assertTrue($advanceFound3->amountLeftToPay() == 600);

        $this->assertTrue(AdvancePayment::find(1)->amount == 800);
        $this->assertTrue(AdvancePayment::find(2)->amount == 200);
        $this->assertTrue(AdvancePayment::find(3)->amount == 600);
        $this->assertTrue(AdvancePayment::find(4)->amount == 400);
        $this->assertTrue(AdvancePayment::find(5)->amount == 400);

        $payeePayment1 = PayeePayment::find($payeePayment->id);
        $this->assertTrue($payeePayment1->status == "paid");

        $this->resetEvents();
    }
}
