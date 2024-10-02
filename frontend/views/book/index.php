<?php
use yii\helpers\Html;
use yii\widgets\ListView;

$this->title = 'Books';
$this->params['breadcrumbs'][] = $this->title;
?>

<h1 class="text-center"><?= Html::encode($this->title) ?></h1>

<div class="container">
    <div class="d-flex justify-content-center">
        <div class="row justify-content-center">
            <?= ListView::widget([
                'dataProvider' => $dataProvider,
                'itemOptions' => ['class' => 'col-md-12 mt-2 mb-4 d-flex align-items-stretch'],
                'layout' => "{items}\n{pager}",
                'itemView' => function ($model, $key, $index, $widget) {
                    return '
                        <div class="card h-100 text-center shadow-sm" style="min-height: 200px;"> <!-- Adjusted card height -->
                            <div class="card-body">
                                <h5 class="card-title">' . Html::encode($model->title) . '</h5>
                                <p class="card-text"><strong>Price:</strong> $' . Html::encode($model->price) . '</p>
                            </div>
                            <div class="card-footer">
                                <p>' . Html::a('View Details', ['view', 'id' => $model->id], ['class' => 'btn btn-primary']) . ' ' .
                        Html::a('Buy', ['buy', 'id' => $model->id], ['class' => 'btn btn-success']) . '</p>
                            </div>
                        </div>
                    ';
                },
            ]) ?>
        </div>
    </div>
</div>
