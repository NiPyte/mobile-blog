<?php

use app\models\User;

class LoginCest
{
    public function _before(\FunctionalTester $I)
    {
        // Create a user.
        // Note: If your app uses password hashing, this plain text password might cause login failure.
        // But since SignupForm saved plain text, this should work.
        $I->haveRecord(User::class, [
            'id' => 100,
            'name' => 'Tester',
            'login' => 'tester@example.com',
            'password' => 'test_password',
        ]);
    }

    public function openLoginPage(\FunctionalTester $I)
    {
        $I->amOnPage('/index-test.php?r=site/login');
        $I->see('Login', 'h1');
    }

    public function loginSuccessfully(\FunctionalTester $I)
    {
        $I->amOnPage('/index-test.php?r=site/login');

        // Ensure we use 'LoginForm[login]' matching your model property
        $I->submitForm('#login-form', [
            'LoginForm[login]' => 'tester@example.com',
            'LoginForm[password]' => 'test_password',
        ]);

        // If this fails, it means password validation in User model expects a Hash.
        $I->dontSeeLink('Login');
        $I->see('Logout');
    }

    public function loginWithWrongPassword(\FunctionalTester $I)
    {
        $I->amOnPage('/index-test.php?r=site/login');

        $I->submitForm('#login-form', [
            'LoginForm[login]' => 'tester@example.com',
            'LoginForm[password]' => 'wrong_pass',
        ]);

        $I->see('Incorrect username or password.');
    }
}