<?php

namespace tests\unit\models;

use app\models\LoginForm;
use Codeception\Test\Unit;

class LoginFormTest extends Unit
{
    public function testLoginNoUser()
    {
        $model = new LoginForm([
            'login' => 'non_existent_user@test.com', // Changed from 'username' to 'login'
            'password' => 'some_password',
        ]);

        $this->assertFalse($model->login(), 'Login should fail for non-existent user');
    }

    public function testLoginWrongPassword()
    {
        // We assume a user exists (created in fixtures or _before, but for unit tests we mock or assume DB state)
        // For simplicity, we just check validation here
        $model = new LoginForm([
            'login' => 'demo',
            'password' => 'wrong_password',
        ]);

        $this->assertFalse($model->login(), 'Login should fail with wrong password');
    }
}