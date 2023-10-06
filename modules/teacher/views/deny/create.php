<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Deny $model */

$this->title = 'Create Deny';
$this->params['breadcrumbs'][] = ['label' => 'Denies', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="deny-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
    

</div>
