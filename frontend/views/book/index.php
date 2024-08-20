<?php

use yii\widgets\ListView;
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Books';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-book-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= ListView::widget([
        'dataProvider' => $dataProvider,
        'itemView' => function ($model, $key, $index, $widget) {
            /* @var $model common\models\Book */
            return
                '<div class="col-lg-4">' .
                '<div class="card mb-4">' .
                '<div class="card-body text-center">' .
                '<h5 class="card-title">' . Html::encode($model->title) . '</h5>' .
                '<p class="card-text">' . Html::encode($model->description) . '</p>' .
                '<p class="card-text"><small class="text-muted">Published: ' . Html::encode($model->publication_year) . '</small></p>' .
                '<a href="' . Url::to(['book/view', 'id' => $model->id]) . '" class="btn btn-primary">View Details</a>' .
                '</div>' .
                '</div>' .
                '</div>';
        },
        'options' => ['class' => 'row'],
    ]) ?>

</div>
