<?php

use yii\bootstrap5\Html;
use yii\bootstrap5\ActiveForm;
use wbraganca\dynamicform\DynamicFormWidget;
use yii\bootstrap5\Modal;

/** @var yii\web\View $this */
/** @var app\models\Test $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="test-form">
    <?php $form = ActiveForm::begin(
        ['id' => 'dynamic-form',
        ]); ?>

    <div class="row">
        <div class="col-sm-6">
            <?= $form->field($modelTest, 'title')->textInput(['maxlength' => true]) ?>
        </div>
        
    </div>
    <div class="row">
        <div class="col-8">
                <?= $form->field($modelTest, 'questions_count')->textInput(['type' => 'number','style' => 'width: 20% !important;']) ?>
        </div>
    </div>
    <?php DynamicFormWidget::begin([
        'widgetContainer' => 'dynamicform_wrapper',
        'widgetBody' => '.container-items',
        'widgetItem' => '.question-item',
        'limit' => 30,
        'min' => 1,
        'insertButton' => '.add-question',
        'deleteButton' => '.remove-question',
        'model' => $modelsQuestion[0],
        'formId' => 'dynamic-form',
        'formFields' => [
            'imageFile',
            'title',
            'level_id',
            'type_id'
        ],
    ]); ?>

    <row class="container-items">
        <?php foreach ($modelsQuestion as $indexQuestion => $modelQuestion): ?>
            <div class="question-item pt-4 pb-4 ps-5 pe-5 mb-2 mt-2" >
            <?php
                if (! $modelQuestion->isNewRecord) {
                    echo Html::activeHiddenInput($modelQuestion, "[{$indexQuestion}]id");
                }
            ?>
                <div class="row">
                 <?= $form->field($modelQuestion, "[{$indexQuestion}]imageFile")->fileInput()->label('Приложение к вопросу') ?> 
                    <div class="col-6">
                        <?= $form->field($modelQuestion, "[{$indexQuestion}]title")->label('Текст вопроса')->textarea(['maxlength' => true]) ?>
                    </div>
                    <div class="col-3">
                        <?= $form->field($modelQuestion, "[{$indexQuestion}]level_id")->label('Сложность вопроса')->dropDownList($levels, ['prompt' => 'Сложность вопроса'])?> 
                    </div>
                    <div class="col-3">
                        <?= $form->field($modelQuestion, "[{$indexQuestion}]type_id")->label('Тип вопроса')->dropDownList($types, ['prompt' => 'Тип вопроса'])?> 
                    </div>
                </div>
            <?= $this->render('_form-answers', [
                'form' => $form,
                'indexQuestion' => $indexQuestion,
                'modelsAnswer' => $modelsAnswer[$indexQuestion],
            ]) ?>

        <? if(Yii::$app->controller->action->id == 'create'): ?>
            <button type="button" class="remove-question btn btn-my-red btn-xs">Удалить вопрос</span></button>
        <?endif;?>
        </div>
<?php endforeach; ?>
    </row>
    <div class="row mb-4">
        <? if(Yii::$app->controller->action->id == 'create'): ?>
            <div style="width: auto;">
                <button type="button" class="add-question btn btn-my-green btn-xs" >Добавить вопрос</button>
            </div>
        <?endif;?>
    </div>
    <?php DynamicFormWidget::end(); ?>
    <div class="form-group">
        <?= Html::submitButton(Yii::$app->controller->action->id == 'create' ? 'Создать' : 'Изменить', ['class' => 'btn btn-my-blue']) ?>
    </div>
<?php ActiveForm::end(); ?>
</div>


<?$js=<<< JS

    $(".dynamicform_wrapper").on("afterInsert", function(e, item) {
        $(item).find('input,textarea,select').each(function(index,element){
            if($(element).attr('type') != 'checkbox'){
                $(element).val('');
            }
            // if($(element).attr('type') == 'checkbox'){
            //     $(element).removeAttr("checked");
            //     // $(element).addClass('form-check-input');
            //     // console.log($(element).prop('checked'));
            // }

            $(element).removeClass('is-valid');
            $(element).removeClass('is-invalid');
            let numOfQuestion = element.id.split('-')[1];
            for( let i = 1; i<=11 ; i++){
                if( element.id == 'answer-'+ numOfQuestion + '-' + i + '-title' ){
                    let a_item = $(element).parents('.answer-item');
                    a_item.remove();
                }
            }
            
           
        });
    });
    
    
JS;
$this->registerJs($js, \yii\web\View::POS_READY);?>  
