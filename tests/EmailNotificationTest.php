<?php

class EmailNotificationTest extends TestCase
{
    protected function modelsToReset()
    {
        return ["Company", "AuthorizationToken", "Credit", "EmailNotification"];
    }

    public function testCreateEmailNotification()
    {
        $this->resetEvents();
        $c = $this->createCompany();
        $en = EmailNotification::createUpdateNotification("Company", $c->id, EmailNotification::COMPANY_NO_CREDIT);
        $this->assertTrue($en->id == 1);
        $this->assertTrue($en->model == "Company");
        $this->assertTrue($en->model_id == $c->id);
        $this->assertTrue($en->type == EmailNotification::COMPANY_NO_CREDIT);
        $this->assertTrue($en->notification_sent != null && $en->notification_sent != "0000-00-00 00:00:00");
        sleep(1);
        $en2 = EmailNotification::createUpdateNotification("Company", $c->id, EmailNotification::COMPANY_NO_CREDIT);
        $this->assertTrue($en2->id == 1);
        $this->assertTrue($en2->model == "Company");
        $this->assertTrue($en2->model_id == $c->id);
        $this->assertTrue($en2->type == EmailNotification::COMPANY_NO_CREDIT);
        $this->assertTrue($en2->notification_sent >= $en->notification_sent);
        $this->resetEvents();
    }

    public function testIsNotificationValid()
    {
        $this->resetEvents();
        $c = $this->createCompany();
        $en = EmailNotification::createUpdateNotification("Company", $c->id, EmailNotification::COMPANY_NO_CREDIT);
        $this->assertTrue($en->isValidNotification(24*60*60));
        $en->notification_sent = date('Y-m-d H:i:s', strtotime('-1 days'));
        $en->save();
        $this->assertFalse($en->isValidNotification(24*60*60));
        $this->resetEvents();
    }
}

