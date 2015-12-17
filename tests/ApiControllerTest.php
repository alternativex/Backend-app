<?php


class ApiControllerTest extends TestCase {

    protected function modelsToReset()
    {
        return ["Company", "User", "PayeePayment", "Credit", "CompanyService", "AuthorizationToken"];
    }

    public function testSimpleCompanyCall()
    {
        $this->resetEvents();
        $this->createModels();
        $response = $this->action('GET', 'ApiController@collection', ['model' => 'company']);
        $result = json_decode($response->getContent(), true);
        $this->assertTrue($result["model"] == "company");
        $this->assertTrue(count($result["items"]) == 2);
        $this->assertTrue($result["offset"] == 0);
        $this->assertTrue($result["limit"] == 10);
        $this->assertTrue($result["count"] == 2);
        $this->assertTrue($result["items"][0]["name"] == "test 1");
        $this->assertTrue($result["items"][0]["paid_credit"]["quantity"] == 10);
        $this->assertTrue($result["items"][0]["free_credit"]["quantity"] == 10);
        $this->assertTrue($result["items"][1]["name"] == "test 2");
        $this->assertTrue($result["items"][1]["paid_credit"]["quantity"] == 10);
        $this->assertTrue($result["items"][1]["free_credit"]["quantity"] == 10);
        $this->resetEvents();
    }

    public function testSimpleLoggedUserCall()
    {
        $this->resetEvents();
        $models = $this->createModels();
        $this->be($models["p1"]);
        $response = $this->action('GET', 'ApiController@collection', ['model' => 'user']);
        $result = json_decode($response->getContent(), true);
        $this->assertTrue($result["model"] == "user");
        $this->assertTrue(count($result["items"]) == 4);
        $this->assertTrue($result["offset"] == 0);
        $this->assertTrue($result["limit"] == 10);
        $this->assertTrue($result["count"] == 4);

        $this->be($models["p4"]);
        $response = $this->action('GET', 'ApiController@collection', ['model' => 'user']);
        $result = json_decode($response->getContent(), true);
        $this->assertTrue($result["model"] == "user");
        $this->assertTrue(count($result["items"]) == 4);
        $this->assertTrue($result["offset"] == 0);
        $this->assertTrue($result["limit"] == 10);
        $this->assertTrue($result["count"] == 4);
        $this->resetEvents();
    }

    public function testSimpleLoggedUserWithCompanyCall()
    {
        $this->resetEvents();
        $models = $this->createModels();
        $this->be($models["p1"]);
        $response = $this->action('GET', 'ApiController@collection', ['model' => 'user']);
        $result = json_decode($response->getContent(), true);
        $this->assertFalse(isset($result["items"][0]["company"]));
        $this->assertFalse(isset($result["items"][1]["company"]));
        $this->assertFalse(isset($result["items"][2]["company"]));

        $response = $this->action('GET', 'ApiController@collection', ['model' => 'user', '_with' => 'company']);
        $result = json_decode($response->getContent(), true);
        $this->assertTrue(isset($result["items"][0]["company"]));
        $this->assertTrue(isset($result["items"][1]["company"]));
        $this->assertTrue(isset($result["items"][2]["company"]));
        $this->assertTrue($result["items"][0]["company"]["id"] == 1);
        $this->assertTrue($result["items"][1]["company"]["id"] == 1);
        $this->assertTrue($result["items"][2]["company"]["id"] == 1);

        $this->resetEvents();
    }

    public function testSimpleLoggedUserSortCall()
    {
        $this->resetEvents();
        $models = $this->createModels();
        $this->be($models["p1"]);
        $response = $this->action('GET', 'ApiController@collection', ['model' => 'user', '_sort' => 'code:desc']);
        $result = json_decode($response->getContent(), true);
        $this->assertTrue($result["items"][0]["code"] == "3333");
        $this->assertTrue($result["items"][1]["code"] == "2222");
        $this->assertTrue($result["items"][2]["code"] == "1111");

        $this->be($models["a"]);
        $response = $this->action('GET', 'ApiController@collection', ['model' => 'user', '_sort' => 'company.name:asc']);
        $result = json_decode($response->getContent(), true);
        $this->assertTrue($result["items"][0]["company_id"] == $models["c1"]->id);
        $this->assertTrue($result["items"][(count($result["items"]) - 1)]["company_id"] == $models["c2"]->id);

        $response = $this->action('GET', 'ApiController@collection', ['model' => 'user', '_sort' => 'company.name:desc']);
        $result = json_decode($response->getContent(), true);
        $this->assertTrue($result["items"][0]["company_id"] == $models["c2"]->id);
        $this->assertTrue($result["items"][(count($result["items"]) - 1)]["company_id"] == $models["c1"]->id);

        $this->resetEvents();
    }

    public function testSimpleLoggedUserFilterCall()
    {
        $this->resetEvents();
        $models = $this->createModels();
        $this->be($models["a"]);
        $response = $this->action('GET', 'ApiController@collection', ['model' => 'user', '_filter[]' => 'email:user']);
        $result = json_decode($response->getContent(), true);
        $this->assertTrue(count($result["items"]) == 7);

        $response = $this->action('GET', 'ApiController@collection', ['model' => 'user', '_filter[]' => 'email:test1']);
        $result = json_decode($response->getContent(), true);
        $this->assertTrue(count($result["items"]) == 2);

        $response = $this->action('GET', 'ApiController@collection', ['model' => 'user', '_filter[]' => 'deleted_at:null']);
        $result = json_decode($response->getContent(), true);
        $this->assertTrue(count($result["items"]) == 8);

        $response = $this->action('GET', 'ApiController@collection', ['model' => 'user', '_filter[]' => 'deleted_at:!null']);
        $result = json_decode($response->getContent(), true);
        $this->assertTrue(count($result["items"]) == 0);

        $response = $this->action('GET', 'ApiController@collection', ['model' => 'user', '_filter' => ['email:test1|or', 'name:user4|or']]);
        $result = json_decode($response->getContent(), true);
        $this->assertTrue(count($result["items"]) == 3);

        $response = $this->action('GET', 'ApiController@collection', ['model' => 'user', '_filter[]' => 'email:=user']);
        $result = json_decode($response->getContent(), true);
        $this->assertTrue(count($result["items"]) == 0);

        $response = $this->action('GET', 'ApiController@collection', ['model' => 'user', '_filter[]' => 'email:!user']);
        $result = json_decode($response->getContent(), true);
        $this->assertTrue(count($result["items"]) == 1);

//        $response = $this->action('GET', 'ApiController@collection', ['model' => 'payeePayment', '_filter[]' => 'user.name:user2']);
//        $result = json_decode($response->getContent(), true);
//        $this->assertTrue(count($result["items"]) == 1);

        $this->resetEvents();
    }

    public function createModels()
    {
        $models = [];
        $models["c1"] = Company::create(["name" => "test 1"]);
        $models["c2"] = Company::create(["name" => "test 2"]);
        $models["a"] = User::create(["name" => "admin", "email" => "admin@test1.com", "company_id" => $models["c1"]->id, "type" => User::TYPE_ADMIN]);
        $models["p1"] = User::create(["name" => "user1", "email" => "user1@test1.com", "company_id" => $models["c1"]->id, "type" => User::TYPE_PAYEE, "code" => "1111"]);
        $models["p2"] = User::create(["name" => "user2", "email" => "user2@test2.com", "company_id" => $models["c1"]->id, "type" => User::TYPE_PAYEE, "code" => "2222"]);
        $models["p3"] = User::create(["name" => "user3", "email" => "user3@test3.com", "company_id" => $models["c1"]->id, "type" => User::TYPE_PAYEE, "code" => "3333"]);
        $models["p4"] = User::create(["name" => "user4", "email" => "user4@test4.com", "company_id" => $models["c2"]->id, "type" => User::TYPE_PAYEE, "code" => "4444"]);
        $models["p5"] = User::create(["name" => "user5", "email" => "user5@test5.com", "company_id" => $models["c2"]->id, "type" => User::TYPE_PAYEE, "code" => "5555"]);
        $models["p6"] = User::create(["name" => "user6", "email" => "user6@test6.com", "company_id" => $models["c2"]->id, "type" => User::TYPE_PAYEE, "code" => "6666"]);
        $models["p7"] = User::create(["name" => "user7", "email" => "user7@test7.com", "company_id" => $models["c2"]->id, "type" => User::TYPE_PAYEE, "code" => "7777"]);
        $models["pp1"] = PayeePayment::create(["amount" => 100, "status" => "unpaid", "payee_code" => $models["p1"]->code, "company_id" => $models["p1"]->company_id]);
        $models["pp2"] = PayeePayment::create(["amount" => 100, "status" => "paid", "payee_code" => $models["p1"]->code, "company_id" => $models["p1"]->company_id]);
        $models["pp3"] = PayeePayment::create(["amount" => 100, "status" => "unpaid", "payee_code" => $models["p1"]->code, "company_id" => $models["p1"]->company_id]);
        $models["pp4"] = PayeePayment::create(["amount" => 100, "status" => "paid", "payee_code" => $models["p2"]->code, "company_id" => $models["p2"]->company_id]);
        $models["pp5"] = PayeePayment::create(["amount" => 100, "status" => "paid", "payee_code" => $models["p2"]->code, "company_id" => $models["p2"]->company_id]);
        $models["pp6"] = PayeePayment::create(["amount" => 100, "status" => "unpaid", "payee_code" => $models["p2"]->code, "company_id" => $models["p2"]->company_id]);
        $models["pp7"] = PayeePayment::create(["amount" => 100, "status" => "unpaid", "payee_code" => $models["p2"]->code, "company_id" => $models["p2"]->company_id]);
        return $models;
    }

} 