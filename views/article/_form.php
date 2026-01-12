<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\Topic; // Import Topic model
use yii\helpers\ArrayHelper; // Import ArrayHelper

/* @var $this yii\web\View */
/* @var $model app\models\Article */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="article-form">

    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>

    <?php
    // Get all topics from DB and map them to [id => name] array
    $topics = ArrayHelper::map(Topic::find()->all(), 'id', 'name');
    ?>
    <?= $form->field($model, 'topic_id')->dropDownList($topics, ['prompt' => 'Select Category...']) ?>

    <?= $form->field($model, 'image')->fileInput() ?>

    <?= $form->field($model, 'tag')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>