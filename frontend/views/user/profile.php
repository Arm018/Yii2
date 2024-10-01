<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\User $user */
/** @var common\models\Balance $balance */ // Change this to the correct type

$this->title = 'User Profile';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-settings">

    <h1><?= Html::encode($user->username) ?></h1>

    <div class="alert alert-info">
        <strong>Referral Link Instructions:</strong>
        <p>
            Your referral link is a unique URL that you can share with others. When someone signs up using your link, you will receive a 10% commission on their purchases.
            Make sure to share your link widely to maximize your earnings!
        </p>
        <p>
            Your referral link: <strong><a href="<?= Html::encode($user->referral_link) ?>" target="_blank"><?= Html::encode($user->referral_link) ?></a></strong>
        </p>
    </div>

    <div class="balance-info">
        <h2>Your Balance</h2>
        <p>
            Current balance: <strong><?= Html::encode($balance->amount) ?> USD</strong>
        </p>
        <p>
            You can request a withdrawal when your balance reaches at least <strong>10 USD</strong>.
        </p>
    </div>

    <p>
        <?= Html::a('Go to Dashboard', ['/site/index'], ['class' => 'btn btn-primary']) ?>
    </p>

</div>
