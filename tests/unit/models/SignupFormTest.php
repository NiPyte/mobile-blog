<?php

namespace tests\unit\models;

use app\models\SignupForm;
use Codeception\Test\Unit;

class SignupFormTest extends Unit
{
    public function testCorrectSignup()
    {
        $model = new SignupForm([
            'name' => 'Test User',
            'login' => 'new_user@example.com',
            'password' => 'password123',
        ]);

        $user = $model->signup();
        $this->assertNotNull($user, 'User should be created');
        $this->assertEquals('Test User', $user->name);
    }

    public function testNotCorrectSignup()
    {
        $model = new SignupForm([
            'name' => 'Test User',
            'login' => 'not-an-email',
            'password' => '123',
        ]);

        $this->assertNull($model->signup(), 'Signup should fail');
        $this->assertArrayHasKey('login', $model->errors);
        $this->assertArrayHasKey('password', $model->errors);
    }
}