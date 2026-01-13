<?php

/** @var yii\web\View $this */

use yii\helpers\Html;

$this->title = 'About Mobile Blog';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-about">
    <div class="p-5 mb-4 bg-light rounded-3">
        <div class="container-fluid py-5">
            <h1 class="display-5 fw-bold"><?= Html::encode($this->title) ?></h1>
            <p class="col-md-8 fs-4">Welcome to the ultimate resource for mobile technology enthusiasts.</p>
        </div>
    </div>

    <div class="row align-items-md-stretch">
        <div class="col-md-6">
            <div class="h-100 p-5 text-white bg-dark rounded-3">
                <h2>Our Mission</h2>
                <p>We aim to provide the most accurate reviews, latest news, and helpful guides for Android and iOS users. Whether you are looking for a flagship or a budget phone, we have you covered.</p>
            </div>
        </div>
        <div class="col-md-6">
            <div class="h-100 p-5 bg-light border rounded-3">
                <h2>Course Work Project</h2>
                <p>This website was developed as a course project for "Designing Web-Oriented Information Systems".</p>
                <p><strong>Tech Stack:</strong> PHP, Yii2 Framework, MySQL, Bootstrap 5.</p>
                <button class="btn btn-outline-secondary" type="button">Contact Admin</button>
            </div>
        </div>
    </div>
</div>