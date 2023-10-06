<?php

use app\models\Group;
use app\models\Test;
use yii\bootstrap5\Html;
use yii\bootstrap5\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\GroupTest $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="group-test-form">

    <?php $form = ActiveForm::begin(); ?>


    <?= $form->field($model, 'test_id')->dropDownList(Test::getTestsList(), ['prompt' => 'Выберите тест']) ?>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-my-green']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
