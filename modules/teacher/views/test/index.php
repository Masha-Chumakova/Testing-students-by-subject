<?php

use app\models\Test;
use yii\bootstrap5\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\widgets\ListView;

/** @var yii\web\View $this */
/** @var app\models\TestSerch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Тесты';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="test-index">

    

    <div class=" d-flex align-items-center justify-content-between">
        <h1 class=" "><?= Html::encode($this->title) ?></h1>
        <?= Html::a('Добавить тест', ['create'], ['class' => 'btn btn-my-green ']) ?>
</div>

    <?php echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= ListView::widget([
        'dataProvider' => $dataProvider,
        'itemOptions' => ['class' => 'item'],
        'layout' => '<ul class="list-group list-group-flush style="background-color:rgba(0, 0, 0, 0) !important;">{items}</ul>',
        'itemView' => function ($model, $key, $index, $widget) {
            return '<li class="list-group-item" style="background-color:rgba(0, 0, 0, 0) !important;padding-left:0px !important; border:none !important; border-bottom: 1px solid rgba(0,0,0,0.125) !important;border-radius:0px !important;">'.Html::a(Html::encode($model->title), ['view', 'id' => $model->id], ['style' => 'color:#3b7ddd']) . '</li>';
        },
    ]) ?>


</div>
