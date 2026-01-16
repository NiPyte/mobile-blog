<?php

namespace tests\unit\models;

use app\models\Article;
use Codeception\Test\Unit;

class ArticleTest extends Unit
{
    public function testValidationFailsWithoutTitle()
    {
        $article = new Article();
        $article->description = 'Some description content';
        $article->date = date('Y-m-d');

        // Use standard PHPUnit assertion
        $this->assertFalse($article->validate(), 'Model should not be valid without title');
        $this->assertArrayHasKey('title', $article->getErrors(), 'Error message should exist for title');
    }

    public function testSaveArticleSuccessfully()
    {
        $article = new Article();
        $article->title = 'Test Article Title';
        $article->description = 'Test Description Content';
        $article->date = date('Y-m-d');

        $this->assertTrue($article->save(), 'Article should be saved successfully');
        $this->assertNotNull($article->id, 'Article should have an ID after saving');
    }
}