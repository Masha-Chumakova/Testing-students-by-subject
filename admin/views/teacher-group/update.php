<?php

use yii\bootstrap5\Html;
use app\models\User;
/** @var yii\web\View $this */
/** @var app\models\UserGroup $model */

$this->title = 'Изменить';
$this->params['breadcrumbs'][] = ['label' => 'User Groups', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<? 
$teachers_with_fio = [];
foreach($teachers as $key => $val){
    $teacherFIO = User::getFIO($key);
    $teachers_with_fio[$key] = $teacherFIO;
}
?>
<div class="user-group-update">
<p>
<?=Html::a("<svg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' class='feather feather-chevron-left align-middle me-2'><polyline points='15 18 9 12 15 6'></polyline></svg>Назад",
    ['/admin/teacher-group'], ['class' => 'a-back d-flex align-items-center'])?>
</p>
<h1><?= Html::encode($this->title) ?></h1>

<?= $this->render('_form', [
    'model' => $model,
    'groups' => $groups,
    'teachers' => $teachers_with_fio
]) ?>

</div>
