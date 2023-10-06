<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use app\models\User;
use app\models\UserGroup;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\helpers\Url;
use app\models\Group;
use app\models\StudentAnswer;
use app\models\Test;
use app\models\Type;

/** @var yii\web\View $this */
/** @var app\models\StudentTest $model */

$this->title = $modelStudentTest->id;
$this->params['breadcrumbs'][] = ['label' => 'Student Tests', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="student-test-view">
    <p>
        <?=Html::a("<svg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' class='feather feather-chevron-left align-middle me-2'><polyline points='15 18 9 12 15 6'></polyline></svg>Тесты студента",
        ['/teacher/student/view?id=' . $modelStudentTest->user_id], ['class' => 'a-back d-flex align-items-center'])?>
    </p>
    <div class="row">
        <div class="col-6">
            <h2><?=User::getFio($modelStudentTest->user_id)?></h2>
            <h3><?=$modelStudentTest->test->title?></h3>
        </div>
        <!-- <div class="col-6 d-flex justify-content-end align-items-center">
            <?php if($modelStudentTest->cheked == 1){?>
                <button class="btn btn-outline-success disabled border-2 btn-lg">Проверено</button>
            <?php }else{?>
                <button class="btn btn-outline-danger disabled border-2 btn-lg">Непроверено</button>
            <?php }?>
            
        </div> -->
    </div>
    
    <?= DetailView::widget([
        'model' => $modelStudentTest,
        'attributes' => [
            // 'id',
            // [
            //     'attribute' => 'test_id',
            //     'label' => 'Тест',
            //     'format' => 'raw',
            //     'value' => function ($modelStudentTest) {
            //             return Html::a($modelStudentTest->test->title, '/teacher/test/view?id=' . $modelStudentTest->test_id, ['class' => 'link-dark']);
            //     }
            // ],
            [
                'attribute' => 'date',
                'label' => 'Дата прохождения теста',
                'value' => function($modelStudentTest){
                    $date = new DateTimeImmutable($modelStudentTest->date);
                    return $date->format('d/m/Y');
                }
            ],
            [
                'attribute' => 'points',
                'label' => 'Баллы за последнюю попытку',
                'value' => function($modelStudentTest){
                    return $modelStudentTest->points . ' / ' . Test::findOne($modelStudentTest->test_id)->max_points; 
                }
            ],
            [
                'attribute' => 'mark',
                'label' => 'Оценка за последнюю попытку'
            ],
          
            [
                'attribute' => 'group_test_id',
                'label' => 'Статистика всей группы',
                'format' => 'raw',
                'value' => function ($modelStudentTest) {
                        $group_title = UserGroup::getGroupTitleByUser($modelStudentTest->user_id);
                        $group_id = Group::getGroupId($group_title);
                        $current_group = Group::getCurrentGroup($group_id);
                        $group_title = Group::findOne($current_group) -> title;
                        return Html::a($modelStudentTest->test->title. '<br>' .'Группа: '. $group_title  , '/teacher/group-test/view?id=' . $modelStudentTest->group_test_id, ['class' => 'link-dark']);
                }

            ],

        ],
    ]) ?>
    

    
<?php

echo $test_list;

?>
</div>

