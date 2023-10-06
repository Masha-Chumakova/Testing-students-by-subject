<?php

use yii\bootstrap5\Html;
use yii\widgets\DetailView;
use yii\widgets\ListView;
use app\models\UserGroup;
use app\models\Group;
use app\models\StudentTest;
use yii\helpers\VarDumper;

/** @var yii\web\View $this */
/** @var app\models\user $model */

$this->title = $modelUser->surname . ' ' . $modelUser->name .' '. $modelUser->patronymic;
$this->params['breadcrumbs'][] = ['label' => 'Users', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>


<div class="user-view info-block">
    <p>
        <?=Html::a("<svg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' class='feather feather-chevron-left align-middle me-2'><polyline points='15 18 9 12 15 6'></polyline></svg>Студенты",
        ['/teacher/student'], ['class' => 'a-back d-flex align-items-center'])?>
    </p>
    
    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Изменить', ['update', 'id' => $modelUser->id], ['class' => 'btn btn-my-blue']) ?>
        <?= Html::a('Удалить', ['delete', 'id' => $modelUser->id], [
            'class' => 'btn btn-my-red',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
        <?php if($modelUser->password):?>
         <?= Html::a('Сбросить пароль', ['student/view?id='. $modelUser->id . '&btn=delete_password'], ['class' => 'btn btn-my-grey', 'data-method' => 'post']) ?>
        <?php endif?>
        <!-- <?= Html::a('Статистика', '/teacher/student-test', ['class' => 'btn btn-success']) ?> -->
    </p>

    <?= DetailView::widget([
        'model' => $modelUser,
        'attributes' => [
            // 'id',
            
            'name',
            'surname',
            'patronymic',
            'entered',
            'graduated',
            [
                'format' => 'raw',
                'label' => 'Группа',
                'value' => function($modelUser){
                    $group_title = UserGroup::getGroupTitleByUser($modelUser->id);
                    $group_id = Group::getGroupId($group_title);
                    $current_group = Group::getCurrentGroup($group_id);
                    $group_title = Group::findOne($current_group) -> title;
                    return Html::a($group_title, ['/teacher/group/view?id=' . $current_group], ['class' => 'link-dark']);
                },
            ],
            // 'role_id',
            // 'auth_key',
        ],
    ]) ?>
        <?=$list?>
    <!-- <?= ListView::widget([
        'dataProvider' => $dataProvider,
        'itemOptions' => ['class' => 'item'],
        'itemView' => function ($model, $key, $index, $widget) use ($modelUser) {
            if($model->cheked == 0){
                return Html::a($model->test->title, ['student-test/view', 'id' => $model->id, 'student_id' =>$modelUser->id], [ 'class' => 'link-danger']) . '<span class="text-muted" style="font-size:12px">&nbsp&nbsp&nbsp необходима проверка</span>';
            }else{
                return Html::a($model->test->title, ['student-test/view', 'id' => $model->id, 'student_id' =>$modelUser->id]);
            }
            
        },
    ]) ?> -->
</div>

