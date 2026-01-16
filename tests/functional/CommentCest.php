<?php

use app\models\User;
use app\models\Article;

class CommentCest
{
    public function _before(\FunctionalTester $I)
    {
        // 1. Create a user
        $I->haveRecord(User::class, [
            'name' => 'Commenter',
            'login' => 'commenter@test.com',
            'password' => '123456',
        ]);

        // 2. Create an article
        $I->haveRecord(Article::class, [
            'id' => 99,
            'title' => 'Article for Comments',
            'description' => 'Discuss this!',
            'date' => date('Y-m-d'),
        ]);

        // 3. Log in
        $I->amOnPage('/index-test.php?r=site/login');
        $I->submitForm('#login-form', [
            'LoginForm[login]' => 'commenter@test.com',
            'LoginForm[password]' => '123456',
        ]);
    }

    public function leaveComment(\FunctionalTester $I)
    {
        // Go to the article view page (using ID 99)
        $I->amOnPage('/index-test.php?r=site/view&id=99');
        $I->see('Article for Comments');

        // Fill the comment form
        $I->fillField('#comment-text', 'This is a test comment!');
        $I->click('Post Comment');

        // Verify the comment appears
        $I->see('This is a test comment!');
        $I->see('Commenter');
    }
}