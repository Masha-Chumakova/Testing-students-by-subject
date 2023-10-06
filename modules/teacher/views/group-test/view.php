<?php

use app\models\GroupTest;
use app\models\Test;
use yii\helpers\Html;
use yii\widgets\DetailView;
use dosamigos\chartjs\ChartJs;
use yii\helpers\VarDumper;
/** @var yii\web\View $this */
/** @var app\models\GroupTest $model */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Group Tests', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>

<div class="group-test-view info-block">
<p>
    <?=Html::a("<svg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' class='feather feather-chevron-left align-middle me-2'><polyline points='15 18 9 12 15 6'></polyline></svg>Группа",
    '/teacher/group/view?id=' . $model->group_id, ['class' => 'a-back d-flex align-items-center'])?>
</p>
    <div class="row d-flex justify-content-between">
        <div class="col-6">
            <h2>Группа: <?= Html::encode($model->group->title) ?> </h2>
            <h4><?= Html::encode($model->test->title) ?> </h4>
        </div>
        <div class="col-6 d-flex justify-content-end align-items-center">
            <?=Html::a('Изменить доступ к тесту', ['/teacher/deny?group_test_id=' .$model->id], ['class' => 'btn btn-my-green'])?>
        </div>

    </div>


    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            [
                'format' => 'raw',
                'attribute' => 'date',
                'value' => function($model) use ($date_str){
                    return $date_str; 
                },
               
                'label' => 'Даты проведения теста'
            ],
            [
                'attribute' => 'avg_points',
                'value' => function($model){
                    if($model -> avg_points !== null){
                        $max_points = Test::findOne($model->test_id)->max_points;
                        return round($model -> avg_points, 2) . ' / ' . $max_points ;
                    }else{
                        return '';
                    }
                },
                'label' => 'Среднее количество баллов за тест'
            ],
            // [
            //     'attribute' => 'val_5',
            //     'format' => 'raw',
            //     'label' => '<a href="#mark-5">Количество оценок 5</a>',
            //     'value' => function($model){
            //         if($model -> val_5 !== null){
            //             return $model -> val_5;
            //         }else{
            //             return '';
            //         }
            //     },
            // ],
            // [
            //     'attribute' => 'val_4',
            //     'format' => 'raw',
            //     'label' => '<a href="#mark-4">Количество оценок 4</a>',
            //     'value' => function($model){
            //         if($model -> val_4 !== null){
            //             return $model -> val_4;
            //         }else{
            //             return '';
            //         }
            //     },
            // ],
            // [
            //     'format' => 'raw',
            //     'label' => '<a href="#mark-3">Количество оценок 3</a>',
            //     'attribute' => 'val_3',
            //     'value' => function($model){
            //         if($model -> val_3 !== null){
            //             return $model -> val_3;
            //         }else{
            //             return '';
            //         }
            //     },
            // ],
            // [
            //     'format' => 'raw',
            //     'label' => '<a href="#mark-2">Количество оценок 2</a>',
            //     'attribute' => 'fails',
            //     'value' => function($model){
            //         if($model -> fails !== null){
            //             return $model -> fails;
            //         }else{
            //             return '';
            //         }
            //     },
            // ],
            // [
            //     'attribute' => 'group_id',
            //     'label' => 'Группа',
            //     'value' => $model->group->title
            // ],
            // [
            //     'attribute' => 'test_id',
            //     'label' => 'Тест',
            //     'value' => $model->test->title
            // ],
            
            
        ],
    ]) ?>
</div>
<div class='group-test-view-marks mt-3 info-block' >
    <?=$list?>
</div>


 <?if($current_marks[5] || $current_marks[4] || $current_marks[3]|| $current_marks[2]):?> 
    <div class="group-view-stat  mt-3 info-block">
   <h2  style="text-align:center">Статистика группы по тесту</h2>
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
               'data' => [$current_marks[5], $current_marks[4], $current_marks[3], $current_marks[2]],// Your dataset
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
               'fontColor' => "#000",
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
    ]);

?> 
</div>  
</div>
 <?endif;?> 




