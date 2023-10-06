<?php

use yii\bootstrap5\Html;
use yii\bootstrap5\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\TestSerch $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="test-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>



    <?= $form->field($model, 'title') ?>

    <div class="form-group mb-3">
        <?= Html::submitButton('Искать', ['class' => 'btn btn-my-blue']) ?>
        <?= Html::a('Сбросить',['/teacher/test'], ['class' => 'btn btn-my-grey']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
