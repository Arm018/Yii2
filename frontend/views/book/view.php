<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $book common\models\Book */

$this->title = $book->title;
$this->params['breadcrumbs'][] = ['label' => 'Books', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-book-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="card">
        <div class="card-body">
            <h5 class="card-title">Description</h5>
            <p class="card-text"><?= Html::encode($book->description) ?></p>

            <h5 class="card-title">Publication Year</h5>
            <p class="card-text"><?= Html::encode($book->publication_year) ?></p>

            <h5 class="card-title">Authors</h5>
            <p class="card-text">
                <?= implode(', ', \yii\helpers\ArrayHelper::getColumn($book->authors, function ($author) {
                    return Html::encode($author->first_name . ' ' . $author->last_name);
                })) ?>
            </p>
        </div>
    </div>

</div>
