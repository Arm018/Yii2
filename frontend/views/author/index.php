<?php

use yii\helpers\Html;
use yii\helpers\Url;

/** @var yii\web\View $this */
/** @var common\models\Author[] $authors */

$this->title = 'Authors';
?>
<div class="author-index">

    <h1 class="text-center"><?= Html::encode($this->title) ?></h1>

    <div class="list-group" style="margin-top: 100px !important;margin-left: 350px !important;">
        <?php foreach ($authors as $author): ?>
            <a style="width: 400px !important;" href="<?= Url::to(['view', 'id' => $author->id]) ?>"
               class="list-group-item list-group-item-action">
                <p class="text-center"><?= Html::encode($author->first_name . ' ' . $author->last_name) ?></p>
            </a>
        <?php endforeach; ?>
    </div>

</div>
