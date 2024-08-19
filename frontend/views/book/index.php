<?php

use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $books common\models\Book[] */

$this->title = 'Books';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-book-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="row">
        <?php foreach ($books as $book): ?>
            <div class="col-lg-4">
                <div class="card mb-4">
                    <div class="card-body">
                        <h5 class="card-title"><?= Html::encode($book->title) ?></h5>
                        <p class="card-text"><?= Html::encode($book->description) ?></p>
                        <p class="card-text"><small class="text-muted">Published: <?= Html::encode($book->publication_year) ?></small></p>
                        <a href="<?= Url::to(['book/view', 'id' => $book->id]) ?>" class="btn btn-primary">View Details</a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

</div>
