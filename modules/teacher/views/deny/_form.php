<?php

use yii\bootstrap5\Html;
use yii\bootstrap5\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\Deny $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="deny-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'true_false')->textInput() ?>

    <?= $form->field($model, 'group_test_id')->textInput() ?>

    <?= $form->field($model, 'user_id')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
