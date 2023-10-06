<?php

use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;

/** @var yii\web\View $this */
/** @var app\models\Group $model */
/** @var ActiveForm $form */
?>
<div class="addgroup">
<? if( Yii::$app -> session -> hasFlash('success') ): ?>
            <div class="alert alert-success" role="alert">
                <?=Yii::$app -> session->getFlash('success') ?>
            </div>
    <? endif ?>
    <?php $form = ActiveForm::begin(); ?>

        <?= $form->field($model, 'title') ?>
    
        <div class="form-group">
            <?= Html::submitButton('Submit', ['class' => 'btn btn-primary']) ?>
        </div>
    <?php ActiveForm::end(); ?>

</div><!-- addgroup -->
<script>
    $('.sidebar-item').removeClass('active');
    $('#sidebar-addgroup').addClass('active');
</script>