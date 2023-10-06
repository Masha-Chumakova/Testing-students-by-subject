<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\StudentTest $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="student-test-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'points')->textInput() ?>

    <?= $form->field($model, 'mark')->textInput() ?>

    <?= $form->field($model, 'test_id')->textInput() ?>

    <?= $form->field($model, 'user_id')->textInput() ?>

    <?= $form->field($model, 'group_test_id')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
