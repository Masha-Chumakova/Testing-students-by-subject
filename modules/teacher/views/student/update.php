<?php

use yii\bootstrap5\Html;

/** @var yii\web\View $this */
/** @var app\models\user $model */

$this->title = 'Изменение информации о студенте';

?>
<div class="user-update info-block">
<p>
        <?=Html::a("<svg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' class='feather feather-chevron-left align-middle me-2'><polyline points='15 18 9 12 15 6'></polyline></svg>Студенты",
        ['/teacher/student'], ['class' => 'a-back d-flex align-items-center'])?>
</p><h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'modelUser' => $modelUser,
        'modelUserGroup' => $modelUserGroup,
        'groups' => $groups
    ]) ?>

</div>
