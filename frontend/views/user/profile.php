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
    <h2 class="mt-3">Referral Instruction</h2>
    <div class="alert alert-info">
        <strong>Referral Link Instructions:</strong>
        <p>
            Your referral link is a unique URL that you can share with others. When someone signs up using your link,
            you will receive a 10% commission on their purchases.
            Make sure to share your link widely to maximize your earnings!
        </p>
        <p>
            Your referral link: <strong><a href="<?= Html::encode($user->referral_link) ?>" target="_blank"><?= Html::encode($user->referral_link) ?></a></strong>
        </p>
    </div>

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
                        <p>Price: $<?= Html::encode($userBook->amount) ?></p>
                        <p>Purchase Date: <?= Yii::$app->formatter->asDate($userBook->purchase_date) ?></p>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <p>You have not purchased any books yet.</p>
        <?php endif; ?>
    </div>

</div>
