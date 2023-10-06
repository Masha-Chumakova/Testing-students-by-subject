<?php

use app\models\Group;
use yii\bootstrap5\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\Group $model */

$this->title = 'Группа: '.$model->title;
$this->params['breadcrumbs'][] = ['label' => 'Groups', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>

<? if( Yii::$app -> session -> hasFlash('success') ): ?>
    <div class="alert alert-success" role="alert">
        <?=Yii::$app -> session->getFlash('success') ?>
    </div>
<? endif ?>
<div class="group-view info-block">
<p>
<?=Html::a("<svg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' class='feather feather-chevron-left align-middle me-2'><polyline points='15 18 9 12 15 6'></polyline></svg>Группы",
    ['/admin/group'], ['class' => 'a-back d-flex align-items-center'])?>
</p>
    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Изменить', ['update', 'id' => $model->id], ['class' => 'btn btn-my-blue']) ?>
        <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-my-red',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            // 'id',
            'title',
            'course',
            'year',
            [
                'attribute' => 'previous_group_id',
                'value' => function($model){
                    if($model -> previous_group_id){
                        return Group::findOne($model->previous_group_id)->title;
                    }else{
                        return '';
                    }
                    
                }
            ]
            
        ],
    ]) ?>

</div>
