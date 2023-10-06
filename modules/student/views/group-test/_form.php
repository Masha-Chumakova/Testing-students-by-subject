<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\GroupTest $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="group-test-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'date')->textInput() ?>

    <?= $form->field($model, 'avg_points')->textInput() ?>

    <?= $form->field($model, 'val_5')->textInput() ?>

    <?= $form->field($model, 'val_4')->textInput() ?>

    <?= $form->field($model, 'val_3')->textInput() ?>

    <?= $form->field($model, 'fails')->textInput() ?>

    <?= $form->field($model, 'group_id')->textInput() ?>

    <?= $form->field($model, 'test_id')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
