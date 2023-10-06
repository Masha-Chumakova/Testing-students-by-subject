<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\GroupTest $model */

$this->title = 'Update Group Test: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Group Tests', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="group-test-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
