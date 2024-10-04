<?php

use yii\helpers\Html;
use yii\helpers\Url;

/** @var yii\web\View $this */
/** @var common\models\User $user */
/** @var common\models\Balance $balance */
/** @var common\models\Order[] $orders */

$this->title = 'User Profile';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-settings">
    <div class="balance-info">
        <h2>Your Balance</h2>
        <p>
            Current balance: <strong>$<?= Html::encode($balance->amount) ?></strong>
        </p>
        <p>
            Commission balance: <strong>$<?= Html::encode($balance->commission_amount) ?></strong>
        </p>
        <?php if ($balance->commission_amount > 10): ?>
            <a href="<?= Url::to(['request-withdrawal']) ?>" class="btn btn-warning">Request Withdrawal</a>
        <?php endif; ?>

        <p>
            You can request a withdrawal when your commission balance reaches at least <strong>$10</strong>.
        </p>
    </div>

    <h2>Your Orders</h2>
    <div>
        <?php if ($orders): ?>
            <ul class="list-group">
                <?php foreach ($orders as $order): ?>
                    <li class="list-group-item" style="margin-top:30px">
                        <h4>Order Total: $<?= Html::encode($order->total_amount) ?></h4>
                        <p>Commission: $<?= Html::encode($order->commission) ?></p>
                        <p>Order Date: <?= Yii::$app->formatter->asDate($order->purchase_date) ?></p>
                        <h5>Items:</h5>
                        <ul>
                            <?php foreach ($order->orderItems as $item): ?>
                                <li>
                                    <strong><?= Html::encode($item->book->title) ?></strong>
                                    (Quantity: <?= Html::encode($item->quantity) ?>,
                                    Amount: $<?= Html::encode($item->amount) ?>)
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <p>You have not placed any orders yet.</p>
        <?php endif; ?>
    </div>
</div>
