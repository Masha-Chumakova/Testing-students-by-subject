<?php
use app\models\StudentTest;
use yii\helpers\VarDumper;

$this->title = 'Главная страница';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="info-block">
    <div class='border-bottom border-2'>
        <h2><?=Yii::$app->user->identity->surname . ' ' .Yii::$app->user->identity->name . ' ' . Yii::$app->user->identity->patronymic?></h2>
        <h3>Группа: <?=$group?></h3>    
    </div>
    <div class='mt-3'>
        <p>Пройдено тестов: <b><?=$tests_count?></b></p>
        <?=$test_list?>
    </div>
    
</div>
