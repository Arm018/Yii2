<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\models\Author;

/** @var yii\web\View $this */
/** @var common\models\Book $model */
/** @var yii\widgets\ActiveForm $form */

?>

<div class="book-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'publication_year')->textInput() ?>

    <?= $form->field($model, 'authorIds')->dropDownList(
        Author::getAuthorList(),
        ['multiple' => true]
    )->label('Authors') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
