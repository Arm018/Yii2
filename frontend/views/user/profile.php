<?php

use yii\helpers\Html;
use yii\helpers\Url;

/** @var yii\web\View $this */
/** @var common\models\User $user */
/** @var common\models\Balance $balance */
/** @var common\models\UserBook[] $books */

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

    <h2>Your Purchased Books</h2>
    <div>
        <?php if ($books): ?>
            <ul class="list-group">
                <?php foreach ($books as $userBook): ?>
                    <li class="list-group-item" style="margin-top:30px">
                        <h5><?= Html::encode($userBook->book->title) ?></h5>
                        <p>Total: $<?= Html::encode($userBook->amount) ?></p>
                        <p>Quantity: <?= Html::encode($userBook->quantity) ?></p>
                        <p>Purchase Date: <?= Yii::$app->formatter->asDate($userBook->purchase_date) ?></p>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <p>You have not purchased any books yet.</p>
        <?php endif; ?>
    </div>

</div>
