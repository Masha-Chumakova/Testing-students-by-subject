<?php

use yii\bootstrap5\Html;
use yii\widgets\DetailView;
use app\models\UserGroup;
use app\models\Group;
use app\models\User;
use yii\widgets\ListView;
use dosamigos\chartjs\ChartJs;
use yii\helpers\VarDumper;

/** @var yii\web\View $this */
/** @var app\models\Group $model */

$this->title = $modelGroup->title;
$this->params['breadcrumbs'][] = ['label' => 'Groups', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<?

?>
<div class="group-view">
<div class="group-view-group-list info-block" >
<p class="d-flex justify-content-between"> 
    
    <?=Html::a("<svg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' class='feather feather-chevron-left align-middle me-2'><polyline points='15 18 9 12 15 6'></polyline></svg>Группы",
    ['/teacher/group'], ['class' => 'a-back d-flex align-items-center'])?>


     <span class="d-flex justify-content-between">
        <?php if($modelGroup->previous_group_id):?>
            <?=Html::a("<svg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' class='feather feather-chevron-left align-middle '><polyline points='15 18 9 12 15 6'></polyline></svg>",['view', 'id' => $modelGroup->previous_group_id], ['class' => 'a-back d-flex align-items-center'])?>
        <?php endif?>

        <?=$modelGroup->year?>/<?=$modelGroup->year%100+1?>

        <?php if($next_group):?>
            <?=Html::a("<svg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' class='feather feather-chevron-right align-middle '><polyline points='9 18 15 12 9 6'></polyline></svg>",['view', 'id' => $next_group->id], ['class' => 'a-back d-flex align-items-center'])
            ?>
        <?php endif;?>
    </span>
    
</p>
<div class=" d-flex justify-content-between align-items-center w-100">
    
        <h1 class="col-8">Группа: <?= Html::encode($this->title) ?></h1>
        <div class="d-flex flex-column justify-content-between  ">
            <a href="#tests" class='btn btn-my-green  '>Тесты группы</a>
            <?=Html::a('Документ', [ 'group/view?id=' . $modelGroup->id . '&btn=document'], ['class' => 'btn btn-my-green mt-3 col-4', 'data-method' => 'post'])?>
        </div>
    
</div>
    

    <?php
        if($modelGroup->previous_group_id){
          $students =  UserGroup::getUsersOfGroup($first_previous_group, $modelGroup ->id);
        }else{
            $students = UserGroup::getUsersOfGroup($modelGroup->id, $modelGroup->id);
        }
    
   $list_of_students = ['<ol class="">'];
    foreach($students as $key => $student){
        if(User::findOne($key)->graduated !== null){
            $date = new DateTimeImmutable(User::findOne($key)->graduated );
            array_push(
                $list_of_students, 
                '<li>'.Html::a($student, ['/teacher/student/view?id='.$key], ['class' => 'a-student-view']). ' (Отчислен: '.$date->format('d/m/Y') .')'.'</li>'
            );
        }else{
            array_push(
                $list_of_students,
                '<li>'.Html::a($student, ['/teacher/student/view?id='.$key], ['class' => 'a-student-view']).'</li>'
            );
        }
        
    }
    array_push($list_of_students, '</ol>');
    foreach($list_of_students as $item_of_list){
        echo $item_of_list;
    }
    ?>
</div>




    <div class="tests group-view-test-list mt-3 info-block">
        <h2 id='tests' style="text-align:center">Тесты группы</h2>
        <div class=" d-flex justify-content-between align-items-center w-100">
            <h4 class="col-2 m-0 border-bottom">Всего тестов: <?=$tests_count?></h4>
            <?=Html::a('Добавить тест', ['/teacher/group-test/create?group_id=' . $modelGroup->id], ['class' => 'btn btn-my-green col-6 '])?>
        </div>
    <?= ListView::widget([
        'dataProvider' => $dataProvider,
        'itemOptions' => ['class' => 'item'],
        'summary' => false,
        'itemView' => function ($model, $key, $index, $widget) {
            return Html::a(Html::encode($model->test->title), ['group-test/view', 'id' => $model->id]);
        },
    ]);
    ?>
    </div>



    <?php if($marks):?>
    <div class="group-view-stat  mt-3 info-block">
    <h2  style="text-align:center">Статистика группы по всем тестам</h2>
    <div class="chart-container mt-3">
<?=ChartJs::widget([
   'type' => 'pie',
   'id' => 'structurePie',
   'options' => [
       'height' => 500,
       'width' => 500,
   ],
   'data' => [
       'radius' =>  "90%",
       'labels' => ['Количество 5', 'Количество 4', 'Количество 3', 'Количество не сдавших тесты'], // Your labels
       'datasets' => [
           [
               'data' => [$marks[5] , $marks[4], $marks[3], $marks[2]],// Your dataset
               'label' => '',
               'backgroundColor' => [
                   '#a4d196',
                   '#bbd0d3',
                   '#fbdb78',
                   '#f0a89c'
               ],
               'borderColor' =>  [
                       '#fff',
                       '#fff',
                       '#fff'
               ],
               'borderWidth' => 1,
               'hoverBorderColor'=>["#999","#999","#999"],
           ]
       ]
   ],
   'clientOptions' => [
       'legend' => [
           'display' => true,
           'position' => 'top',
           'labels' => [
               'fontSize' => 14,
               'fontColor' => "#425062",
           ]
       ],
       'tooltips' => [
           'enabled' => true,
           'intersect' => true
       ],
       'hover' => [
           'mode' => true
       ],
       'maintainAspectRatio' => false,

   ],
   'plugins' =>
       new \yii\web\JsExpression("
       [{
           afterDatasetsDraw: function(chart, easing) {
               var ctx = chart.ctx;


               let result = chart.data.datasets[0].data.reduce(function(sum, elem) {
               	return sum + elem;
               }, 0);

               chart.data.datasets.forEach(function (dataset, i) {
                   var meta = chart.getDatasetMeta(i);
                   if (!meta.hidden) {
                       meta.data.forEach(function(element, index) {
                           // Draw the text in black, with the specified font
                           ctx.fillStyle = 'rgb(0, 0, 0)';

                           var fontSize = 16;
                           var fontStyle = 'normal';
                           var fontFamily = 'Helvetica';
                           ctx.font = Chart.helpers.fontString(fontSize, fontStyle, fontFamily);

                           // Just naively convert to string for nows


                           let percent = dataset.data[index] * 100 / result;
                           percent =  percent.toFixed(2);
                           if(percent != 0){
                               var dataString = percent + '%' + ' - ' + dataset.data[index].toString() ;
                           }else{
                               var dataString = '';
                           }


                           // Make sure alignment settings are correct
                           ctx.textAlign = 'center';
                           ctx.textBaseline = 'middle';

                           var padding = 5;
                           var position = element.tooltipPosition();
                           ctx.fillText(dataString, position.x, position.y - (fontSize/2) - padding);
                       });
                   }
               });
           }
       }]")
])

?>
</div>  
</div>
<?php endif;?>
</div>

<!-- https://github.com/2amigos/yii2-chartjs-widget -->