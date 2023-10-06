<?

use yii\bootstrap5\ActiveForm;
use yii\widgets\Pjax;
use app\models\Type;
?>

    <div class="questions-list d-flex justify-content-start flex-row gap-4 flex-wrap">
        <?= $questions_str;?>
    </div>


<div class="question-field">
    <?

   

        Pjax::begin([
            'id' => 'questions-pjax',
            'enablePushState' => false
        ]);?>
        <h3><?=$question_title?></h3>
        <div class="mt-3">
            <? $form = ActiveForm::begin([

            ]);
            if($question -> type_id == Type::getTypeId('Один правильный ответ')){
                foreach($answers as $answer){
                    echo $form->field($modelStudentAnswer, 'answer_id')-> radio() -> label($answer['title']) ;
                }
                
            }else if($question -> type_id == Type::getTypeId('Несколько правильных ответов')){
                foreach($answers as $answer){
                    echo $form->field($modelStudentAnswer, 'answer_id')-> checkbox() -> label($answer['title']) ;
                }
            }
            
            ActiveForm::end();?>
        </div>
        

       <? Pjax::end();
    ?>
</div>