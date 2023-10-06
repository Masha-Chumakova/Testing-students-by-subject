<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\StudentAnswer $model */

$this->title = 'Update Student Answer: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Student Answers', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="student-answer-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
