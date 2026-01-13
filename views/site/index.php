<?php

/** @var yii\web\View $this */
/** @var app\models\Article[] $articles */
/** @var app\models\Topic[] $topics */
/** @var yii\data\Pagination $pages */

use yii\widgets\LinkPager;
use yii\helpers\Url;

$this->title = 'Mobile Blog';
?>

<div class="site-index">
    <div class="row">

        <div class="col-md-8">
            <h2 class="mb-4 border-bottom pb-2">
                <?= isset($currentTopic) ? 'Category: ' . \yii\helpers\Html::encode($currentTopic->name) : 'Latest Posts' ?>
            </h2>

            <?php foreach ($articles as $article): ?>
                <?= $this->render('_article', ['model' => $article]) ?>
            <?php endforeach; ?>

            <div class="d-flex justify-content-center mt-4">
                <?= LinkPager::widget([
                        'pagination' => $pages,
                        'options' => ['class' => 'pagination'],
                        'linkOptions' => ['class' => 'page-link'],
                        'pageCssClass' => 'page-item',
                        'disabledPageCssClass' => 'page-item disabled',
                        'prevPageCssClass' => 'page-item',
                        'nextPageCssClass' => 'page-item',
                ]) ?>
            </div>
        </div>

        <div class="col-md-4">
            <div class="p-4 mb-3 bg-light rounded">
                <h4 class="fst-italic">Categories</h4>
                <ul class="list-group list-group-flush bg-transparent">
                    <?php foreach ($topics as $topic): ?>
                        <li class="list-group-item bg-transparent d-flex justify-content-between align-items-center">
                            <a href="<?= Url::to(['site/topic', 'id' => $topic->id]) ?>" class="text-decoration-none">
                                <?= $topic->name ?>
                            </a>
                            <span class="badge bg-primary rounded-pill">
                                <?= $topic->getArticles()->count() ?>
                            </span>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>

            <div class="p-4 mb-3 bg-light rounded">
                <h4 class="fst-italic">About</h4>
                <p class="mb-0">Welcome to the best blog about mobile technology. Read reviews of iPhone, Samsung, and more.</p>
            </div>
        </div>

    </div>
</div>