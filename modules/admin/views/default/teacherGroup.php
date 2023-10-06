<?php

use yii\bootstrap5\Html;
use yii\bootstrap5\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\teacherGroup $model */
/** @var ActiveForm $form */
?>
<div class="teacherGroup">

    <?php $form = ActiveForm::begin(); ?>

        <?= $form->field($model, 'teacher')->dropDownList(
            $teachers,
            ['prompt' => 'Выберите группу']
                

        ) ?>
        <?= $form->field($model, 'group')->dropDownList(
            $groups,
            ['prompt' => 'Выберите группу']
                

        ) ?>
    
        <div class="form-group">
            <?= Html::submitButton('Submit', ['class' => 'btn btn-primary']) ?>
        </div>
    <?php ActiveForm::end(); ?>

</div><!-- teacherGroup -->
<?
$items = [];
    foreach($teachers as $val){
        // $items[]=[$val['name']];
        
    }
    var_dump($teachers[22]);

?>

