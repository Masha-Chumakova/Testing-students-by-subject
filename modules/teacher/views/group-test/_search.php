<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\GroupTestSearch $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="group-test-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'date') ?>

    <?= $form->field($model, 'avg_points') ?>

    <?= $form->field($model, 'val_5') ?>

    <?= $form->field($model, 'val_4') ?>

    <?php // echo $form->field($model, 'val_3') ?>

    <?php // echo $form->field($model, 'fails') ?>

    <?php // echo $form->field($model, 'group_id') ?>

    <?php // echo $form->field($model, 'test_id') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
