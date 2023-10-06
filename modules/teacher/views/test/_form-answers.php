<?php


use yii\bootstrap5\Html;

use wbraganca\dynamicform\DynamicFormWidget;


?>


<?php DynamicFormWidget::begin([

    'widgetContainer' => 'dynamicform_inner',

    'widgetBody' => '.container-answers',

    'widgetItem' => '.answer-item',

    'limit' => 10,

    'min' => 1,

    'insertButton' => '.add-answer',

    'deleteButton' => '.remove-answer',

    'model' => $modelsAnswer[0],

    'formId' => 'dynamic-form',

    'formFields' => [
        'true_false',
        'title'
    ],

]); ?>
        
        <div class="row">
        
            <h4 class="col-8">Ответы</h4>    
            <div class="col-4">
                <button type="button" class="mb-3 add-answer btn btn-my-green btn-xs " style="width: auto;"   ><span class="glyphicon glyphicon-plus"></span></button>
            </div>   
        </div>
        <div class="container-answers">
             <?php foreach ($modelsAnswer as $indexAnswer => $modelAnswer): ?>
                <row class="answer-item" >
                    <?php
                        if (! $modelAnswer->isNewRecord) {
                            echo Html::activeHiddenInput($modelAnswer, "[{$indexQuestion}][{$indexAnswer}]id");
                        }
                    ?>
                    <div class="row d-flex align-items-center">

                        <div class="col-1 d-flex justify-content-around">
                            <?= $form->field($modelAnswer, "[{$indexQuestion}][{$indexAnswer}]true_false")->label(false)->checkbox() ?>
                        </div>

                        <div class="col-7">
                            <?= $form->field($modelAnswer, "[{$indexQuestion}][{$indexAnswer}]title")->label(false)->textInput(['maxlength' => true]) ?>
                        </div>

                        <div class="col-4  ">
                            <button type="button" class="mb-3 remove-answer btn btn-my-red btn-xs"><span class="glyphicon glyphicon-minus"></span></button>
                        </div>

                     </div>
                </row>

            <?php endforeach; ?>

        </div>

    

<?php DynamicFormWidget::end(); ?>

