<?php

use yii\helpers\Html;
use yii\helpers\Url;

/** @var yii\web\View $this */
/** @var app\models\Article $article */
/** @var app\models\Topic[] $topics */
/** @var app\models\Comment[] $comments */
/** @var app\models\Comment $comment */

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
                        &nbsp;|&nbsp;
                        <i class="bi bi-tags"></i>
                        <?php
                        if ($article->tag) {
                            $tags = explode(',', $article->tag);
                            foreach($tags as $tag):
                                $tag = trim($tag);
                                if(empty($tag)) continue;
                                ?>
                                <a href="<?= Url::to(['site/tag', 'tag' => $tag]) ?>" class="badge bg-secondary text-decoration-none">
                                    <?= Html::encode($tag) ?>
                                </a>
                            <?php endforeach;
                        }
                        ?>
                    </p>

                    <hr>

                    <div class="article-content">
                        <?= nl2br(Html::encode($article->description)) ?>
                    </div>

                    <div class="social-share mt-4">
                        <h5>Share this post:</h5>
                        <?php
                        $pageUrl = Url::to(['site/view', 'id' => $article->id], true);
                        $title = $article->title;
                        ?>

                        <a href="https://www.facebook.com/sharer/sharer.php?u=<?= urlencode($pageUrl) ?>" target="_blank" class="btn btn-primary btn-sm">
                            <i class="bi bi-facebook"></i> Facebook
                        </a>

                        <a href="https://twitter.com/intent/tweet?text=<?= urlencode($title) ?>&url=<?= urlencode($pageUrl) ?>" target="_blank" class="btn btn-dark btn-sm">
                            <i class="bi bi-twitter-x"></i> X (Twitter)
                        </a>

                        <a href="https://t.me/share/url?url=<?= urlencode($pageUrl) ?>&text=<?= urlencode($title) ?>" target="_blank" class="btn btn-info btn-sm text-white">
                            <i class="bi bi-telegram"></i> Telegram
                        </a>
                    </div>
                </article>

                <div class="mt-5 d-flex justify-content-between">
                    <a href="<?= Url::to(['site/index']) ?>" class="btn btn-outline-secondary">&larr; Back to Blog</a>
                </div>

                <div class="mt-5">
                    <h3 class="mb-4">Comments (<?= count($comments) ?>)</h3>

                    <?php if (!empty($comments)): ?>
                        <?php foreach ($comments as $c): ?>
                            <div class="card mb-3 border-0 bg-light">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <h6 class="card-subtitle mb-2 text-primary">
                                            <?= Html::encode($c->user ? $c->user->name : 'Unknown User') ?>
                                        </h6>
                                        <span class="text-muted small"><?= $c->date ?></span>
                                    </div>
                                    <p class="card-text">
                                        <?= Html::encode($c->text) ?>
                                    </p>
                                    <?php if (!Yii::$app->user->isGuest): ?>
                                        <button class="btn btn-sm btn-link p-0 reply-btn"
                                                data-user="<?= Html::encode($c->user ? $c->user->name : 'User') ?>">
                                            Reply
                                        </button>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p class="text-muted">No comments yet. Be the first!</p>
                    <?php endif; ?>

                    <hr>

                    <?php if (!Yii::$app->user->isGuest): ?>
                        <div class="comment-form mt-4">
                            <h4>Leave a Comment</h4>
                            <?php $form = \yii\widgets\ActiveForm::begin([
                                    'action' => ['site/view', 'id' => $article->id],
                                    'options' => ['class' => 'form-horizontal'],
                                    'fieldConfig' => [
                                            'template' => "{input}\n{error}",
                                    ],
                            ]); ?>

                            <?= $form->field($comment, 'text')->textarea([
                                    'id' => 'comment-text',
                                    'class' => 'form-control',
                                    'rows' => 3,
                                    'placeholder' => 'Write your opinion here...'
                            ])->label(false) ?>

                            <div class="form-group mt-2">
                                <?= Html::submitButton('Post Comment', ['class' => 'btn btn-primary']) ?>
                            </div>

                            <?php \yii\widgets\ActiveForm::end(); ?>
                        </div>
                    <?php else: ?>
                        <div class="alert alert-warning">
                            Please <a href="<?= Url::to(['site/login']) ?>">Login</a> to leave a comment.
                        </div>
                    <?php endif; ?>
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

<?php
$this->registerJs("
    $('.reply-btn').click(function() {
        var user = $(this).data('user');
        var textarea = $('#comment-text');
        var currentText = textarea.val();
        
        textarea.val('@' + user + ', ' + currentText);
        textarea.focus();
        
        // Scroll to form
        $('html, body').animate({
            scrollTop: textarea.offset().top - 100
        }, 500);
    });
");
?>