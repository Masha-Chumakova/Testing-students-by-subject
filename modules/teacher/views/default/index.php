<?php
use app\models\StudentTest;
use yii\helpers\VarDumper;

$this->title = 'Главная страница';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="info-block">
    <div class='border-bottom border-2'>
        <h2><?=Yii::$app->user->identity->surname . ' ' .Yii::$app->user->identity->name . ' ' . Yii::$app->user->identity->patronymic?></h2>
    </div>
    <div class='mt-3'>
       <?=$all_tests?>
    </div>
</div>
    <div class='mt-3 info-block'>
        <h2 class="border-bottom border-2 ">Необходима проверка</h2>
        <?=$unchek_tests?>
    </div>
    
