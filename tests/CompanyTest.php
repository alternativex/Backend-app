<?php

class CompanyTest extends TestCase {

    protected function modelsToReset()
    {
        return ["Company", "AuthorizationToken", "Credit"];
    }

    public function testCompanyCreatedHasCredits()
    {
        $this->resetEvents();
        $c = $this->createCompany();
        $this->assertTrue($c->credit(Credit::FREE)->first()->quantity == 10);
        $this->assertTrue($c->credit(Credit::PAID)->first()->quantity == 10);
        $this->resetEvents();
    }

    public function testCompanyCreatedHasValidCredits()
    {
        $this->resetEvents();
        $c = $this->createCompany();
        $this->assertTrue(Company::hasCredits($c->id));
        $this->resetEvents();
    }

    public function testCompanyCreatedHasntValidCredits()
    {
        $this->resetEvents();
        $c = $this->createCompany();
        $this->resetCredit($c->credit(Credit::FREE)->first());
        $this->resetCredit($c->credit(Credit::PAID)->first());
        $this->assertFalse(Company::hasCredits($c->id));
        $this->resetEvents();
    }

    private function resetCredit($credit)
    {
        $credit->quantity = 0;
        $credit->save();
    }

    public function testdecrementCredits()
    {
        $this->resetEvents();
        $c = $this->createCompany();
        $this->assertTrue($c->credit(Credit::FREE)->first()->quantity == 10);
        $this->assertTrue($c->credit(Credit::PAID)->first()->quantity == 10);
        $this->assertTrue(Company::decrementCredits($c->id));
        $this->assertTrue($c->credit(Credit::FREE)->first()->quantity == 9);
        $this->assertTrue($c->credit(Credit::PAID)->first()->quantity == 10);
        $this->assertTrue(Company::decrementCredits($c->id));
        $this->assertTrue($c->credit(Credit::FREE)->first()->quantity == 8);
        $this->assertTrue($c->credit(Credit::PAID)->first()->quantity == 10);
        $this->assertTrue(Company::decrementCredits($c->id));
        $this->assertTrue(Company::decrementCredits($c->id));
        $this->assertTrue(Company::decrementCredits($c->id));
        $this->assertTrue(Company::decrementCredits($c->id));
        $this->assertTrue(Company::decrementCredits($c->id));
        $this->assertTrue(Company::decrementCredits($c->id));
        $this->assertTrue(Company::decrementCredits($c->id));
        $this->assertTrue(Company::decrementCredits($c->id));
        $this->assertTrue($c->credit(Credit::FREE)->first()->quantity == 0);
        $this->assertTrue($c->credit(Credit::PAID)->first()->quantity == 10);
        $this->assertTrue(Company::decrementCredits($c->id));
        $this->assertTrue($c->credit(Credit::FREE)->first()->quantity == 0);
        $this->assertTrue($c->credit(Credit::PAID)->first()->quantity == 9);
        $this->assertTrue(Company::decrementCredits($c->id));
        $this->assertTrue($c->credit(Credit::FREE)->first()->quantity == 0);
        $this->assertTrue($c->credit(Credit::PAID)->first()->quantity == 8);
        $this->assertTrue(Company::decrementCredits($c->id));
        $this->assertTrue(Company::decrementCredits($c->id));
        $this->assertTrue(Company::decrementCredits($c->id));
        $this->assertTrue(Company::decrementCredits($c->id));
        $this->assertTrue(Company::decrementCredits($c->id));
        $this->assertTrue(Company::decrementCredits($c->id));
        $this->assertTrue(Company::decrementCredits($c->id));
        $this->assertTrue(Company::decrementCredits($c->id));
        $this->assertTrue($c->credit(Credit::FREE)->first()->quantity == 0);
        $this->assertTrue($c->credit(Credit::PAID)->first()->quantity == 0);
        $this->assertFalse(Company::decrementCredits($c->id));
        $this->resetEvents();
    }
}
