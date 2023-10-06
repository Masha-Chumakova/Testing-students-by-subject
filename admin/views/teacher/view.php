<?php

use yii\bootstrap5\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\user $model */

$this->title = $model->surname . ' ' . $model->name .' '. $model->patronymic;
$this->params['breadcrumbs'][] = ['label' => 'Users', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>


<div class="user-view">
<p>
    <?=Html::a("<svg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' class='feather feather-chevron-left align-middle me-2'><polyline points='15 18 9 12 15 6'></polyline></svg>Преподаватели",
    ['/admin/teacher'], ['class' => 'a-back d-flex align-items-center'])?>
</p>
    <? if( Yii::$app -> session -> hasFlash('success') ): ?>
            <div class="alert alert-success" role="alert">
                <?=Yii::$app -> session->getFlash('success') ?>
            </div>
    <? endif ?>
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

            'name',
            'surname',
            'patronymic',
            // 'role_id',
            // 'auth_key',
        ],
    ]) ?>

</div>
