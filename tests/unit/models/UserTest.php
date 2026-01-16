<?php

namespace tests\unit\models;

use app\models\User;
use Codeception\Test\Unit;

class UserTest extends Unit
{
    public function testFindUserById()
    {
        // Create user first
        $user = new User();
        $user->name = 'UnitTester';
        $user->login = 'unit@test.com';
        $user->password = '123456';
        $user->save();

        // Try to find
        $foundUser = User::findOne($user->id);
        $this->assertNotNull($foundUser);
        $this->assertEquals('UnitTester', $foundUser->name);
    }

    public function testFindUserByUsername()
    {
        // Create user first
        $user = new User();
        $user->name = 'UnitTester2';
        $user->login = 'unit2@test.com';
        $user->password = '123456';
        $user->save();

        // Assuming your User model has findByUsername or you use findOne(['login' => ...])
        $foundUser = User::findOne(['login' => 'unit2@test.com']);
        $this->assertNotNull($foundUser);
    }
}