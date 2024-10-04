<?php

use yii\grid\GridView;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Users and Referrals';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'username',
            'email',
            'created_at:date',
            [
                'label' => 'Referral Code',
                'value' => function ($model) {
                    return $model->referral_code;
                },
                'format' => 'text',
            ],
            [
                'label' => 'Referral Count',
                'value' => function ($model) {
                    return $model->getReferredCount();
                },
                'format' => 'text',
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{delete}',
            ],
        ],
    ]); ?>
</div>
