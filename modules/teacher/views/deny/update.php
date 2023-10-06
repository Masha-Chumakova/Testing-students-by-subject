<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Deny $model */

$this->title = 'Update Deny: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Denies', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="deny-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
