<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\StudentTestSearch $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="student-test-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'points') ?>

    <?= $form->field($model, 'mark') ?>

    <?= $form->field($model, 'test_id') ?>

    <?= $form->field($model, 'user_id') ?>

    <?php // echo $form->field($model, 'group_test_id') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
