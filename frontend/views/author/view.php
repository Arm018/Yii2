<?php
use yii\helpers\Html;
use yii\helpers\Url;

/** @var yii\web\View $this */
/** @var common\models\Author $author */

$this->title = $author->first_name . ' ' . $author->last_name;
?>
<div class="author-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p><strong>First Name:</strong> <?= Html::encode($author->first_name) ?></p>
    <p><strong>Last Name:</strong> <?= Html::encode($author->last_name) ?></p>
    <p><strong>Biography:</strong> <?= Html::encode($author->biography) ?></p>

    <p>
        <?= Html::a('Back to list', ['index'], ['class' => 'btn btn-primary']) ?>
    </p>

</div>
