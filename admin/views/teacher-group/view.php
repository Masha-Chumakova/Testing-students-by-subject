<?php

use yii\bootstrap5\Html;
use yii\widgets\DetailView;
use app\models\Group;
use app\models\User;
/** @var yii\web\View $this */
/** @var app\models\UserGroup $model */

$this->title = 'Группа: '.Group::getGroupTitle($model->group_id);
$this->params['breadcrumbs'][] = ['label' => 'User Groups', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<p>
<?=Html::a("<svg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' class='feather feather-chevron-left align-middle me-2'><polyline points='15 18 9 12 15 6'></polyline></svg>Назад",
    ['/admin/teacher-group'], ['class' => 'a-back d-flex align-items-center'])?>
</p>
<? if( Yii::$app -> session -> hasFlash('success') ): ?>
    <div class="alert alert-success" role="alert">
        <?=Yii::$app -> session->getFlash('success') ?>
    </div>
<? endif ?>
<div class="user-group-view info-block">

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
        [
            'attribute' => 'group_id',
            'value' => Group::getGroupTitle($model->group_id)
        ],
        [
            'attribute' => 'user_id',
            'value' => User::getFio($model->user_id)
        ],
    ],
]) ?>

</div>
