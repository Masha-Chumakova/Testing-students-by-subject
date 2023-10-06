<?php

use app\models\GroupTest;
use app\models\StudentTest;
use app\models\Test;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\widgets\ListView;
use yii\helpers\VarDumper;
/** @var yii\web\View $this */
/** @var app\models\GroupTestSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Доступные тесты';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="group-test-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <!-- <p>
        <?= Html::a('Create Group Test', ['create'], ['class' => 'btn btn-success']) ?>
    </p> -->

    <!-- <?php echo $this->render('_search', ['model' => $searchModel]); ?> -->
    <!-- <?VarDumper::dump(Yii::$app->session->get('questions_square'));?> -->
    <?= ListView::widget([
        'dataProvider' => $dataProvider,
        'itemOptions' => ['class' => 'item'],
        'itemView' => function ($model, $key, $index, $widget) {
            return Html::a(Html::encode($model->test->title), ['/student/test'], [  'class'=>'test-n',
            'data' => [
                'method' => 'post',
                'params' => [
                    'question' => 0, 
                    'id'=> $model->id,
                ],
            ]
        ]);
        },
    ]) ?>


</div>
