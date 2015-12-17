<?php

class UserTest extends TestCase {

    protected function modelsToReset()
    {
        return ["User"];
    }

    public function testUpdateEmailAndPasswordSuccess()
    {
        $this->resetEvents();
        $user = new User();
        $user->save();
        $this->assertTrue($user->email == null);
        $this->assertTrue($user->password == null);

        $email = "test@test.com";
        $password = "1234";
        $resp = User::updateEmailAndPassword($user->id, $email, $password);
        $this->assertTrue($resp);
        $user = User::find($user->id);

        $this->assertTrue($user->email == $email);
        $this->assertTrue($user->password != null);

        $this->assertTrue(Auth::attempt(['email' => $email, 'password' => $password]) == 1);

        $this->resetEvents();
    }

    public function testUpdateEmailAndPasswordInvalidEmail()
    {
        $this->resetEvents();
        $user = new User();
        $user->save();
        $this->assertTrue($user->email == null);
        $this->assertTrue($user->password == null);

        $invalidEmail = "test";
        $password = "1234";
        $resp = User::updateEmailAndPassword($user->id, $invalidEmail, $password);
        $this->assertFalse($resp);
        $user = User::find($user->id);

        $this->assertTrue($user->email == null);
        $this->assertTrue($user->password == null);

        $this->resetEvents();
    }

    public function testUpdateEmailAndPassword_EmailAlreadyExists()
    {
        $this->resetEvents();
        $email = "test@test.com";
        $password = "1234";
        $user = User::create(["email" => $email]);

        $newUser = new User();
        $newUser->save();
        $this->assertTrue($newUser->id != $user->id);
        $this->assertTrue($newUser->email == null);
        $this->assertTrue($newUser->password == null);

        $resp = User::updateEmailAndPassword($newUser->id, $email, $password);
        $this->assertFalse($resp);
        $this->assertTrue($newUser->email == null);
        $this->assertTrue($newUser->password == null);

        $this->resetEvents();
    }

    public function testUpdateEmailAndPassword_EmailAlreadySaved()
    {
        $this->resetEvents();
        $email = "test@test.com";
        $newEmail = "test123123@test.com";
        $password = "1234";

        $newUser = new User();
        $newUser->email = $email;
        $newUser->save();

        $this->assertTrue($newUser->email != null);
        $this->assertTrue($newUser->password == null);

        $resp = User::updateEmailAndPassword($newUser->id, $newEmail, $password);
        $this->assertFalse($resp);
        $this->assertTrue($newUser->email == $email);
        $this->assertTrue($newUser->password == null);

        $this->resetEvents();
    }
}
