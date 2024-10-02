<?php

/** @var yii\web\View $this */

use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'BookStore';
?>
<div class="site-index">

    <div class="jumbotron text-center bg-transparent">
        <h1 class="display-4"><?= Html::encode($this->title) ?></h1>
        <p class="lead">Welcome to BookStore. See all the  books and authors with ease.</p>
    </div>

    <div class="body-content">

        <div class="row justify-content-center">
            <div class="col-md-4 mt-5">
                <div class="card text-center">
                    <div class="card-body">
                        <h5 class="card-title">Books</h5>
                        <p class="card-text">View books.</p>
                        <a href="<?= Url::to(['/book/index']) ?>" class="btn btn-outline-secondary">Watch Books</a>
                    </div>
                </div>
            </div>

            <div class="col-md-4 mt-5">
                <div class="card text-center">
                    <div class="card-body">
                        <h5 class="card-title">Authors</h5>
                        <p class="card-text">View authors.</p>
                        <a href="<?= Url::to(['/author/index']) ?>" class="btn btn-outline-secondary">Watch Authors</a>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
