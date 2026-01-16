<?php

use app\models\User;

class ArticleCest
{
    public function _before(\FunctionalTester $I)
    {
        // Create an Admin user
        $I->haveRecord(User::class, [
            'id' => 1,
            'name' => 'Admin',
            'login' => 'admin@test.com',
            'password' => 'admin123',
        ]);

        // Log in using the direct URL string
        $I->amOnPage('/index-test.php?r=site/login');

        // Submit the login form (using 'login' instead of 'username')
        $I->submitForm('#login-form', [
            'LoginForm[login]' => 'admin@test.com',
            'LoginForm[password]' => 'admin123',
        ]);
    }

    public function createArticle(\FunctionalTester $I)
    {
        // Go to article creation page
        $I->amOnPage('/index-test.php?r=article/create');
        $I->see('Create Article', 'h1');

        // Submit the article form
        // We removed 'content' because your model only has 'description'
        $I->submitForm('#w0', [
            'Article[title]' => 'Selenium & Codeception',
            'Article[description]' => 'Testing is cool',
        ]);

        // Verify the result
        $I->see('Selenium & Codeception', 'h1');
    }
}