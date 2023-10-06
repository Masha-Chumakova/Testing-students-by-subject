<?php

use app\models\StudentTest;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\widgets\ListView;

/** @var yii\web\View $this */
/** @var app\models\StudentTestSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Student Tests';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="student-test-index">

    <h1><?= Html::encode($this->title) ?></h1>


    <!-- <?php echo $this->render('_search', ['model' => $searchModel]); ?> -->

    <?= ListView::widget([
        'dataProvider' => $dataProvider,
        'itemOptions' => ['class' => 'item'],
        'itemView' => function ($model, $key, $index, $widget) {
            return Html::a(Html::encode($model->id), ['view', 'id' => $model->id]);
        },
    ]) ?>


</div>
