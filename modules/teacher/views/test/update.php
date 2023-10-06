<?php

use yii\bootstrap5\Html;

/** @var yii\web\View $this */
/** @var app\models\Test $model */

$this->title = 'Изменение теста';

?>
<div class="test-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'modelTest' => $modelTest,

        'modelsQuestion' => $modelsQuestion,
 
        'modelsAnswer' => $modelsAnswer,

        'levels' => $levels,

        'types' => $types
    ]) ?>

</div>
