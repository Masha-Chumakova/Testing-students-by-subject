<?php

use yii\bootstrap5\Html;
use yii\bootstrap5\ActiveForm;
use app\models\Group;

/** @var yii\web\View $this */
/** @var app\models\Group $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="group-form info-block">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'course')->textInput(['maxlength' => true]) ?>
    <?if ($model->isNewRecord) {?>
         <?= $form->field($model, 'year')->textInput(['maxlength' => true, 'type' => 'number', 'value' => date('Y')]) ?>
    <?}else{?>
         <?= $form->field($model, 'year')->textInput(['maxlength' => true, 'type' => 'number']) ?>
        <?}?>
   
    <?= $form->field($model, 'previous_group_id')->dropDownList(Group::getGroupsList(), ['prompt' => 'Предыдущая группа']) ?>
    
    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-my-green']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
