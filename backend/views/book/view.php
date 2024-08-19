<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Book */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Books', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="book-view">
    <p>
        <?= Html::a('Back', ['index'], ['class' => 'btn btn-secondary']) ?>
    </p>
    <h1><?= Html::encode($this->title) ?></h1>



    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'title',
            'description:ntext',
            'publication_year',
            [
                'label' => 'Authors',
                'value' => function ($model) {
                    return implode(', ', array_map(function ($author) {
                        return $author->first_name . ' ' . $author->last_name;
                    }, $model->authors));
                },
            ],
        ],
    ]) ?>

</div>
