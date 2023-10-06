<?php

use app\models\user;
use app\models\UserGroup;
use app\models\Group;
use yii\bootstrap5\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var app\models\UserSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Студенты';
?>
<?php if( Yii::$app -> session -> hasFlash('success') ): ?>
    <div class="alert alert-success" role="alert">
        <?=Yii::$app -> session->getFlash('success') ?>
    </div>
<?php endif ?>
<div class="user-index info-block">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Добавить студента', ['create'], ['class' => 'btn btn-my-green']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            // 'id',
            'surname',
            'name',
            'patronymic',
            // 'entered',
            [
                'attribute' => 'entered',
                'format' => ['datetime', 'php:d/m/Y ']
            ],
            
            [
                
                'attribute' => 'group',
                'format' => 'raw',
                'value' => function($dataProvider){
                    $group_id = $dataProvider -> getCurrentGroupById($dataProvider->id);
                    $group_title = Group::getGroupTitle($group_id);
                    // $dataProvider->getCurrentGroupById();
                    return Html::a($group_title, ['/teacher/group/view?id=' . $group_id], ['class' => 'link-dark']);
                },
                'label' => 'Группа'
                // 'filter' => Html::activeDropDownList(
                //     $searchModel,
                //     'group',
                //    Group::find() -> select('title')->indexBy('id')->column(),
                //     ['class' => 'form-control', 'prompt' => 'Группа']
                // )
            ],
            // 'role_id',
            //'auth_key',
            [
                'class' => ActionColumn::class,
                'urlCreator' => function ($action, user $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id' => $model->id]);
                 }
            ],
        ],
    ]); ?>
<pre>

</div>
