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
                'itemView' => function ($model) {
                    return '
                        <div class="card h-100 text-center shadow-sm" style="min-height: 250px;">
                            <div class="card-body">
                                <h5 class="card-title">' . Html::encode($model->title) . '</h5>
                                <p class="card-text"><strong>Price:</strong> $' . Html::encode($model->price) . '</p>
                            </div>
                            <div class="card-footer">
                                ' . Html::beginForm(['cart/add', 'id' => $model->id], 'post', ['class' => 'form-inline']) . ' 
                                    <div class="form-group">
                                        <label for="quantity_' . $model->id . '" class="sr-only">Quantity:</label>
                                        <input type="number" id="quantity_' . $model->id . '" name="quantity[' . $model->id . ']" min="1" value="1" class="form-control" style="width: 70px;text-align:center; margin-left: 75px;">
                                    </div>
                                    <p class="mb-0 mt-3">' .
                        Html::a('View Details', ['view', 'id' => $model->id], ['class' => 'btn btn-primary']) . ' ' .
                        Html::submitButton('Add to Cart', ['class' => 'btn btn-success']) .
                        '</p>
                                ' . Html::endForm() . '
                            </div>
                        </div>
                    ';
                },
            ]) ?>
        </div>
    </div>
</div>
