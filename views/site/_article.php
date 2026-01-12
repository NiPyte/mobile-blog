<?php
use yii\helpers\Html;
use yii\helpers\Url;
?>

<div class="card mb-4 shadow-sm">
    <div class="row g-0">
        <div class="col-md-4">
            <a href="<?= Url::to(['site/view', 'id' => $model->id]) ?>">
                <img src="<?= $model->getImage() ?>" class="img-fluid rounded-start" alt="<?= Html::encode($model->title) ?>" style="width: 100%; height: 200px; object-fit: cover;">
            </a>
        </div>

        <div class="col-md-8">
            <div class="card-body">
                <h5 class="card-title">
                    <a href="<?= Url::to(['site/view', 'id' => $model->id]) ?>" class="text-decoration-none text-dark">
                        <?= Html::encode($model->title) ?>
                    </a>
                </h5>

                <p class="card-text text-muted small">
                    <i class="bi bi-calendar"></i> <?= $model->getDate() ?> &nbsp;|&nbsp;
                    <i class="bi bi-eye"></i> <?= $model->viewed ?> &nbsp;|&nbsp;
                    <i class="bi bi-folder"></i> <?= $model->topic ? $model->topic->name : 'No Category' ?>
                </p>

                <p class="card-text">
                    <?= \yii\helpers\StringHelper::truncate(Html::encode($model->description), 150) ?>
                </p>

                <a href="<?= Url::to(['site/view', 'id' => $model->id]) ?>" class="btn btn-outline-primary btn-sm">Read More &rarr;</a>
            </div>
        </div>
    </div>
</div>