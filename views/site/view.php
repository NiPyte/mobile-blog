<?php

use yii\helpers\Html;
use yii\helpers\Url;

/** @var yii\web\View $this */
/** @var app\models\Article $article */
/** @var app\models\Topic[] $topics */

$this->title = $article->title;
$this->params['breadcrumbs'][] = ['label' => 'Blog', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="site-view">
    <div class="row">

        <div class="col-md-8">
            <article class="blog-post">
                <div class="mb-3">
                    <img src="<?= $article->getImage() ?>" class="img-fluid rounded" alt="<?= Html::encode($article->title) ?>" style="width: 100%;">
                </div>

                <h1 class="blog-post-title"><?= Html::encode($article->title) ?></h1>

                <p class="blog-post-meta text-muted">
                    <i class="bi bi-calendar"></i> <?= $article->getDate() ?>
                    by <a href="#"><?= $article->user ? $article->user->name : 'Unknown' ?></a>
                    &nbsp;|&nbsp;
                    <i class="bi bi-folder"></i> <?= $article->topic ? $article->topic->name : 'No Category' ?>
                    &nbsp;|&nbsp;
                    <i class="bi bi-eye"></i> <?= $article->viewed ?> views
                </p>

                <hr>

                <div class="article-content">
                    <?= nl2br(Html::encode($article->description)) ?>
                </div>
            </article>

            <div class="mt-5 d-flex justify-content-between">
                <a href="<?= Url::to(['site/index']) ?>" class="btn btn-outline-secondary">&larr; Back to Blog</a>

            </div>

            <div class="mt-5">
                <h3>Comments</h3>
                <div class="alert alert-light border">
                    Comments functionality coming soon...
                </div>
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
        </div>

    </div>
</div>