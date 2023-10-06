<?php

use app\models\Answer;
use app\models\Level;
use yii\bootstrap5\Html;
use yii\widgets\DetailView;
use app\models\Question;
use app\models\Test;
use yii\helpers\VarDumper;

/** @var yii\web\View $this */
/** @var app\models\Test $model */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Tests', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="test-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Изменить', ['update', 'id' => $model->id], ['class' => 'btn btn-my-green']) ?>
        <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-my-red',
            'data' => [
                'confirm' => 'Вы действительно хотите удалить тест?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

   
 
  
   
<?
$list = [];
foreach($model->questions as $question){
    if($question->level_id == Level::getLevelId('Лёгкий')){
        $level = 'question-easy';
    }elseif($question->level_id == Level::getLevelId('Средний')){
        $level = 'question-mid';
    }else{
        $level = 'question-hard';
    }
    array_push(
        $list, 
        "<ul class='test-list ' >".
        "<li class='list-group-item list-test-question  {$level}' style='font-weight:bold'>". 
        $question->title.
        "</li>");
    foreach($question->answers as $answer){
        if( $answer -> true_false == 1){
            array_push(
                $list, 
                "<li class='list-group-item list-test-item-correct'>". 
                $answer->title. 
                "</li>");
        }else{
            array_push(
                $list, 
                "<li class='list-group-item list-test-item-incorrect '>".
                $answer->title.
                "</li>");
        }
    }
    array_push(
        $list, 
        "</ul>");

}
foreach($list as $item){
    echo $item;
}

?>
    

</div>
