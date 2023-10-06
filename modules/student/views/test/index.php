<?php

use yii\bootstrap5\Html;
use yii\bootstrap5\ActiveForm;
use yii\widgets\Pjax;
use app\models\Type;
use aneeshikmat\yii2\Yii2TimerCountDown\Yii2TimerCountDown;
use aayaresko\timer\Timer;
use app\models\Test;
use yii\helpers\VarDumper;
?>




    
<div class="test-title">
    <div class="title">
        <?=$test_title?>
    </div>
</div>


     <?php   Pjax::begin([
            'id' => 'questions-pjax',
            'enablePushState' => false,
            'enableReplaceState' => false,
            'timeout' => 5000
        ]);?>
        <div class="questions-list d-flex justify-content-start flex-row gap-4 flex-wrap">
            <?= $questions_str ?>
        </div>

        <div class="question-field">
            <h3><?=$question->title?></h3>
            <div class="mt-3">
                <?php $form = ActiveForm::begin([
                    'id' => 'test-form'
                ]);?>

                <?=$form->field($modelStudentAnswer, 'user_id')->hiddenInput(['value' => Yii::$app->user->identity->id])->label(false)?>

                <?=$form->field($modelStudentAnswer, 'question_id')->hiddenInput(['value' => $question->id])->label(false)?>
                <?=$form->field($modelStudentAnswer, 'attempt')->hiddenInput(['value' => $attempt])->label(false)?>
                <?php if($question->img):?>
                  <img src="<?=$question->img?>" style='height:150px' />  
                <?php endif?>
                <?php if($question -> type_id == Type::getTypeId('Один правильный ответ')):?>
                    <div class="answers-test">
                        <?=$form->field($modelStudentAnswer, 'cheked')->hiddenInput(['value' => 1])->label(false)?>
                       <?=$form->field($modelStudentAnswer, 'answer_id')-> radioList($answers)->label(false)?>
                    </div>
                    
                <?php elseif ($question -> type_id == Type::getTypeId('Несколько правильных ответов')):?>
                    <div class="answers-test">
                        <?=$form->field($modelStudentAnswer, 'cheked')->hiddenInput(['value' => 1])->label(false)?>
                        <?=$form->field($modelStudentAnswer, 'answer_id')-> checkboxList($answers) -> label(false) ;?>
                    </div>
                <?php elseif($question -> type_id == Type::getTypeId('Ввод ответа от студента')):?>
                    <div class="answers-test">
                        <?=$form->field($modelStudentAnswer, 'cheked')->hiddenInput(['value' => 0])->label(false)?>
                        <?=$form->field($modelStudentAnswer, 'answer_id')->hiddenInput(['value' => key($answers)])->label(false)?>
                        <?=$form->field($modelStudentAnswer, 'answer_title')->textInput(['value' => ''])-> label(false) ;?>
                    </div>
                <?php endif?>
                <div class="form-group">
                    <?=Html::submitButton('Ответить', ['class' => 'btn btn-my-green mt-2', 'id' => 'btn_ans',
                    'data' => [
                        'method' => 'post',
                        'params' => [
                            'question' => $current_question, 
                            'id'=> $group_test_id,
                        ],
                    ]
                    ]) ?>
                </div>
            
                <?php ActiveForm::end();?>
            </div>
        </div>

       <?php Pjax::end();
       
    ?>
