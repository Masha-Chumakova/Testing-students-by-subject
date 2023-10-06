<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\GroupTest $model */

$this->title = 'Create Group Test';
$this->params['breadcrumbs'][] = ['label' => 'Group Tests', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="group-test-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
