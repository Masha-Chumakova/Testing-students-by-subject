<?php

use app\models\user;
use yii\bootstrap5\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use app\models\Group;
/** @var yii\web\View $this */
/** @var app\models\UserGroupSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Преподаватели и группы';
$this->params['breadcrumbs'][] = $this->title;
?>
<? if( Yii::$app -> session -> hasFlash('success') ): ?>
    <div class="alert alert-success" role="alert">
        <?=Yii::$app -> session->getFlash('success') ?>
    </div>
<? endif ?>
<div class="user-group-index info-block">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Добавить группу преподавателю', ['create'], ['class' => 'btn btn-my-green']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            // 'id',
            [
                'attribute' => 'group_id',
                'content' => function ($dataProvider) {
                        return $dataProvider->getGroupTitle();
                }
            ],
            [
                'attribute' => 'user_id',
                'content' => function ($dataProvider) {
                        return $dataProvider->getTeacherOfGroup();
                }, 
                
            ],
            
            [
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id' => $model->id]);
                 }
            ],
        ],
    ]); ?>


</div>
