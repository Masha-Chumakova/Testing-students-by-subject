<?php

use yii\bootstrap5\Html;
use yii\bootstrap5\ActiveForm;
use yii\jui\DatePicker;
/** @var yii\web\View $this */
/** @var app\models\user $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="user-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($modelUser, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($modelUser, 'surname')->textInput(['maxlength' => true]) ?>

    <?= $form->field($modelUser, 'patronymic')->textInput(['maxlength' => true]) ?>
    
    <?=$form -> field($modelUser, 'email', ['enableAjaxValidation' => true]) ->textInput()?>

    <?=$form -> field($modelUserGroup, 'group_id') -> dropDownList($groups, ['prompt' => 'Выберите группу'])?>

    <?=$form -> field($modelUser, 'entered') ->textInput(['type' => 'date'])?>

    <?=$form -> field($modelUser, 'graduated') ->textInput(['type' => 'date'])?>

    

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-my-green']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
