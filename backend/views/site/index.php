<?php

/** @var yii\web\View $this */

use yii\helpers\Url;

$this->title = 'My Yii Application';
?>
<div class="site-index">

    <div class="jumbotron text-center bg-transparent">
        <h2 class="display-4">Welcome to Admin Dashboard!</h2>
        <div style="margin-top: 50px">
            <a href="<?= Url::to(['/books']) ?>" class="btn btn-outline-secondary">Manage Books</a>
            <a href="<?= Url::to(['/author/index']) ?>" class="btn btn-outline-secondary">Manage Authors</a>
        </div>
    </div>


</div>
