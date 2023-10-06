<?php

/** @var yii\web\View $this */
/** @var yii\bootstrap5\ActiveForm $form */
/** @var app\models\LoginForm $model */

use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;

$this->title = 'Авторизация';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="container site-login">
    

    
    <div class="form-signin">
    <h1><?= Html::encode($this->title) ?></h1>
    <?php $form = ActiveForm::begin([
        'id' => 'login-form',
        'layout' => 'horizontal',
        'fieldConfig' => [
            'template' => "{label}\n{input}\n{error}",
            'labelOptions' => ['class' => 'col-lg-1 col-form-label mr-lg-3'],
            'inputOptions' => ['class' => 'col-lg-3 form-control'],
            'errorOptions' => ['class' => 'col-lg-7 invalid-feedback'],
        ],
    ]); ?>
        <div class="form-floating">
            <?= $form->field($model, 'login')->textInput(['autofocus' => true]) ?>
        </div>

        <div class="form-floating">
            <?= $form->field($model, 'password')->passwordInput() ?>
        </div>

        <!-- <div class="form-floating">
            <?= $form->field($model, 'rememberMe')->checkbox([
                // 'template' => "<div class=\"offset-lg-1 col-lg-3 custom-control custom-checkbox\">{input} {label}</div>\n<div class=\"col-lg-8\">{error}</div>",
            ]) ?>
        </div> -->
        <div class='form-login-btn'>
            <?= Html::submitButton('Вход', ['class' => ' btn btn-lg btn-my-blue', 'name' => 'login-button']) ?>
        </div> 

    <?php ActiveForm::end(); ?> 
    </div>
    


  

</div>
