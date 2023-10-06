<?php

use app\models\Group;
use app\models\UserGroup;
use yii\bootstrap5\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\widgets\ListView;

/** @var yii\web\View $this */
/** @var app\models\GroupSerch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Группы';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="group-index info-block">

    
    <p class="d-flex justify-content-between"> 
    
    <h1><?= Html::encode($this->title) ?></h1>


     
    
</p>

     <!-- <?php echo $this->render('_search', ['model' => $searchModel]); ?>  -->
    
     <!-- ListView::widget([
        'dataProvider' => $dataProvider,
        'itemOptions' => ['class' => 'item'],
        'itemView' => function ($model, $key, $index, $widget) {
            if($model->course == 4){
                array_push($fourth_course, 'Html::a(Html::encode($model->title), ["view", "id" => $model->id], ["class" => "btn btn-danger mt-2"])' ) ;
            }else{
                return Html::a(Html::encode($model->title), ['view', 'id' => $model->id], ['class' => 'btn btn-success mt-2']);
            }
            
        },
    ])  -->
    
    <?php
    $first_course = [];
    $second_course = [];
    $third_course = [];
    $fourth_course = [];
    $september_december = range(9, 12);

    foreach($groups as $key => $val){
        if( in_array( date('M'), $september_december ) ){
            if( Group::findOne($key)->year == date('Y') ){

                if(Group::findOne($key)->course == 4 ){
                    $fourth_course[$key] = $val;
          
                  }elseif(Group::findOne($key)->course == 3 ){
                      $third_course[$key] = $val;
          
                  }elseif(Group::findOne($key)->course == 2 ){
                      $second_course[$key] = $val;
          
                  }else{
                      $first_course[$key] = $val;
                  }
            }
        }else{
            if( Group::findOne($key)->year + 1 == date('Y') ){

                if(Group::findOne($key)->course == 4 ){
                    $fourth_course[$key] = $val;
          
                  }elseif(Group::findOne($key)->course == 3 ){
                      $third_course[$key] = $val;
          
                  }elseif(Group::findOne($key)->course == 2 ){
                      $second_course[$key] = $val;
          
                  }else{
                      $first_course[$key] = $val;
                  }
            }
        }
       
        
    }
    
    
    ?>
    <div class="mt-3 ">

        <button id='fourth_course' class='btn btn-my-green  pe-1'>
            4 курс
            <span class='ms-2'>
               <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevrons-up align-middle "><polyline points="17 11 12 6 7 11"></polyline><polyline points="17 18 12 13 7 18"></polyline></svg>
            </span>
        </button>

        <ul class=' course-list d-none mt-2' id="fourth_course_list">
            <?php if( !empty($fourth_course)){
             foreach($fourth_course as $key => $val){
                echo  Html::a("<li class=' list-course-item'><span>".$val."</span></li>", ['view', 'id' => $key], ['class' => 'link-dark group-link']);
            } 
            }else{
                echo 'Нет данных.';
            }
            ?>
        </ul>
    </div>

    <div class="mt-3">
        <button id='third_course' class='btn btn-my-green pe-1'>
            3 курс
            <span class='ms-2'>
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevrons-up align-middle "><polyline points="17 11 12 6 7 11"></polyline><polyline points="17 18 12 13 7 18"></polyline></svg>
            </span>
        </button>
        <ul class='course-list d-none mt-2' id="third_course_list">
            <?php if( !empty($third_course)){
            foreach($third_course as $key => $val){
                echo  Html::a("<li class=' list-course-item'><span>".$val."</span></li>", ['view', 'id' => $key], ['class' => 'link-dark group-link']);
            } 
            }else{
                echo 'Нет данных.';
            }
            ?>
        </ul>
    </div>

    <div class="mt-3">
        <button id='second_course' class='btn btn-my-green pe-1'>
            2 курс
            <span class='ms-2'>
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevrons-up align-middle "><polyline points="17 11 12 6 7 11"></polyline><polyline points="17 18 12 13 7 18"></polyline></svg>
            </span>
        </button>
        <ul class='course-list d-none mt-2' id="second_course_list">
            <?php if( !empty($second_course)){
            foreach($second_course as $key => $val){
                echo  Html::a("<li class=' list-course-item'><span>".$val."</span></li>", ['view', 'id' => $key], ['class' => 'link-dark group-link']);
            } 
            }else{
                echo 'Нет данных.';
            }   
            ?>
        </ul>
    </div>

    <div class="mt-3">
        <button id='first_course' class='btn btn-my-green pe-1'>
            1 курс
            <span class='ms-2'>
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevrons-up align-middle "><polyline points="17 11 12 6 7 11"></polyline><polyline points="17 18 12 13 7 18"></polyline></svg>
            </span>
        </button>
        <ul class='course-list d-none mt-2' id="first_course_list">
            
            <?php
            if( !empty($first_course)){
                foreach($first_course as $key => $val){
                    echo  Html::a("<li class=' list-course-item'><span>".$val."</span></li>", ['view', 'id' => $key], ['class' => 'link-dark group-link']);
                } 
            }else{
                echo 'Нет данных.';
            }
           
            ?>
        </ul>
    </div>
</div>
<pre>
    