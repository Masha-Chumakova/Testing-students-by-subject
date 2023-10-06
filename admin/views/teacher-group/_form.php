<?php

use yii\bootstrap5\Html;
use yii\bootstrap5\ActiveForm;

//** @var yii\web\View $this */
/** @var app\models\UserGroup $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="user-group-form info-block">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'group_id')->dropDownList($groups, ['prompt' => 'Выберите группу']) ?>

    <?= $form->field($model, 'user_id')->dropDownList($teachers, ['prompt' => 'Выберите преподавателя'])?>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-my-green']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
