<?php

use app\models\Deny;
use app\models\Group;
use app\models\GroupTest;
use app\models\User;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\widgets\Pjax;
/** @var yii\web\View $this */
/** @var app\models\DenySearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Доступ';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="deny-index info-block">
    
    <h1>Ограничение доступа для группы <?=Group::getGroupTitle(GroupTest::findOne($group_test_id)->group_id)?></h1>

    <?php $deny_for_all = '?btn=deny-all' . '&group_test_id=' . Yii::$app->request->get('group_test_id');?>
    <?php $access_for_all = '?btn=access-all' . '&group_test_id=' . Yii::$app->request->get('group_test_id');?>
    <p class="d-flex justify-content-start gap-2">
        <?= Html::a('Ограничить для всей группы', [$deny_for_all], ['class' => 'btn btn-my-red', 'data-method' => 'post']) ?>
        <?= Html::a('Разрешить для всей группы', [$access_for_all], ['class' => 'btn btn-my-green', 'data-method' => 'post']) ?>
    </p>

    <?php Pjax::begin(); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            // ['class' => 'yii\grid\SerialColumn'],

            // 'id',
            [
                'attribute' => 'true_false',
                'label' => 'Доступ',
                'filter' => array("0"=>"Запрещён", "1"=>"Разрешён"),
                'value' => function($dataProvider){
                    if($dataProvider->true_false){
                        return 'Разрешён';
                    }else{
                        return 'Запрещён';
                    }
                },
                'contentOptions' => function($dataProvider){
                    if($dataProvider->true_false){
                        return ['class' => 'table-success'];
                    }else{
                        return ['class' => 'table-danger'];
                    }
                }
            ],
            [
                'attribute' => 'user_id',
                'label' => 'Студент',
                'value' => function($dataProvider){
                    return User::getFIO($dataProvider->user_id);
                }
            ],
            [
                'label' => 'Изменить доступ',
                'format' => 'raw',
                'value' => function($dataProvider){
                    if($dataProvider->true_false){
                        $deny = '?btn=deny&id=' . $dataProvider->id. '&group_test_id=' . Yii::$app->request->get('group_test_id');
                        return 
                        "<div class='d-flex justify-content-center'>". 
                        Html::a('<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-x-lg" viewBox="0 0 16 16">
                        <path d="M2.146 2.854a.5.5 0 1 1 .708-.708L8 7.293l5.146-5.147a.5.5 0 0 1 .708.708L8.707 8l5.147 5.146a.5.5 0 0 1-.708.708L8 8.707l-5.146 5.147a.5.5 0 0 1-.708-.708L7.293 8 2.146 2.854Z"/>
                      </svg>', [$deny], ['class' => 'btn btn-my-red', 'style' => 'width:80%' , 'data-method' => 'post', 'data-bs-toggle'=>'tooltip', 'data-bs-placement'=>'top', 'title'=>'Запретить доступ']) . "</div";
                    }else{
                        $access = '?btn=access&id=' . $dataProvider->id. '&group_test_id=' . Yii::$app->request->get('group_test_id');
                        return "<div class='d-flex justify-content-center'>" . Html::a('<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-check-lg" viewBox="0 0 16 16">
                        <path d="M12.736 3.97a.733.733 0 0 1 1.047 0c.286.289.29.756.01 1.05L7.88 12.01a.733.733 0 0 1-1.065.02L3.217 8.384a.757.757 0 0 1 0-1.06.733.733 0 0 1 1.047 0l3.052 3.093 5.4-6.425a.247.247 0 0 1 .02-.022Z"/>
                      </svg>', [$access], ['class' => 'btn btn-my-green', 'style' => 'width:80%' , 'data-method' => 'post', 'data-bs-toggle'=>'tooltip', 'data-bs-placement'=>'top', 'title'=>'Разрешить доступ']) . "</div";
                    }
                },
                'contentOptions' => ['style' => 'width:10%; white-space: normal;'],
            ],
            // [
            //     'class' => ActionColumn::className(),
            //     'urlCreator' => function ($action, Deny $model, $key, $index, $column) {
            //         return Url::toRoute([$action, 'id' => $model->id]);
            //      }
            // ],
        ],
    ]); ?>
    
    <?php Pjax::end(); ?>
   
</div>
