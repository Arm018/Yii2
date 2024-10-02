<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\Withdrawal[] $withdrawals */

$this->title = 'Withdrawals';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="withdrawal-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <table class="table table-striped">
        <thead>
        <tr>
            <th>ID</th>
            <th>User ID</th>
            <th>Amount</th>
            <th>Status</th>
            <th>Request Date</th>
            <th>Actions</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($withdrawals as $withdrawal): ?>
            <tr>
                <td><?= Html::encode($withdrawal->id) ?></td>
                <td><?= Html::encode($withdrawal->user_id) ?></td>
                <td><?= Html::encode($withdrawal->amount) ?></td>
                <td><?= Html::encode($withdrawal->status) ?></td>
                <td><?= Html::encode($withdrawal->request_date) ?></td>
                <td>
                    <?= Html::a('Approve', ['approve', 'id' => $withdrawal->id], [
                        'class' => 'btn btn-success',
                        'data' => [
                            'confirm' => 'Are you sure you want to approve this withdrawal?',
                            'method' => 'post',
                        ],
                    ]) ?>
                    <?= Html::a('Decline', ['decline', 'id' => $withdrawal->id], [
                        'class' => 'btn btn-danger',
                        'data' => [
                            'confirm' => 'Are you sure you want to decline this withdrawal?',
                            'method' => 'post',
                        ],
                    ]) ?>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>

</div>
