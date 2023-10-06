<?php

use app\models\Group;
use app\models\UserGroup;
use yii\bootstrap5\Html;

/** @var yii\web\View $this */
/** @var app\models\GroupTest $model */

$this->title = 'Добавление теста группе ' . Group::getGroupTitle($group_id);
$this->params['breadcrumbs'][] = ['label' => 'Group Tests', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="group-test-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'group_id' => $group_id
    ]) ?>



</div>
