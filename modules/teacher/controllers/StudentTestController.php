<?php

namespace app\modules\teacher\controllers;

use Yii;
use app\models\StudentTest;
use app\models\StudentTestSearch;
use app\controllers\AppTeacherController;
use app\models\Level;
use app\models\Question;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\StudentAnswerSearch;
use app\models\Test;
use yii\helpers\VarDumper;
use app\models\StudentAnswer;
use yii\bootstrap5\Html;
use app\models\Type;
use DateTimeImmutable;

/**
 * StudentTestController implements the CRUD actions for StudentTest model.
 */
class StudentTestController extends AppTeacherController
{
    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
                'verbs' => [
                    'class' => VerbFilter::className(),
                    'actions' => [
                        'delete' => ['POST'],
                    ],
                ],
            ]
        );
    }

    /**
     * Lists all StudentTest models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new StudentTestSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single StudentTest model.
     * @param int $id ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {

        
        $user_id = Yii::$app->request->get('student_id');
        $modelStudentTest = StudentTest::findOne($id);
        $group_test_id = $modelStudentTest->group_test_id;
       
        $modelTest = Test::findOne(['id' => $modelStudentTest->test_id]);
        $searchModel = new StudentAnswerSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);
        $dataProvider->query->andWhere(['user_id' => $modelStudentTest->user_id]);
       
        
        if(Yii::$app->request->isPost){
            $btn = Yii::$app->request->get('btn');
            $answer_id = Yii::$app->request->get('answer_id');
            $student_id = Yii::$app->request->get('student_id');
            $attempt = Yii::$app->request->get('attempt');
            if($btn){
                switch($btn){
                    case 'confirm_answer' : StudentAnswer::changeAnswer($answer_id, $student_id, 1, $attempt );break;
                    case 'cancel_answer' : StudentAnswer::changeAnswer($answer_id, $student_id, 0, $attempt);break;
                }
                
                if(StudentTest::getIsChecked($student_id, $group_test_id, $attempt)){
                    StudentTest::changeStudentTest($modelTest->id, $group_test_id, $attempt, $student_id);
                    return $this->redirect('/teacher/student-test/view?id='. $id . '&student_id= '.$student_id);
                }   
            }
        }

        $questions = [];

        $student_tests = StudentTest::find()
                        ->where(['user_id' => $user_id, 'group_test_id' => $group_test_id])
                        ->orderBy(['attempt' => SORT_DESC])
                        ->all();
                        
        $test_list = [];
        foreach($student_tests as $student_test){
            $student_answers = [];
            foreach ($modelTest->questions as $question) {
                foreach ($question->studentAnswers as $student_answer) {
                    if($student_answer->user_id == $user_id && $student_answer->attempt == $student_test->attempt ){
                        array_push($questions, $student_answer->question_id);
                        array_push($student_answers, $student_answer->answer_id);
                    }
                }
            }
            $date = new DateTimeImmutable($student_test->date);
            array_push(
                $test_list,
                "<div class='row d-flex justify-content-beetwen border-bottom align-items-end mt-5'>".
                    "<h2 class='col-6 mb-0'>".
                        'Попытка №'.
                        $student_test->attempt. '<br>' .
                       "<span class='fs-4 text-muted'>" .'Полученная оценка: ' . $student_test->mark. '</span>'.
                    "</h2>"
                    ."<p class='text-muted col-6 text-end mb-0'>". $date->format('d/m/Y') ."</p>"
                ."</div>"
            ); 
            $student_answers_all = StudentAnswer::find()
                                ->where(['user_id' => $user_id, 'attempt' => $student_test->attempt])
                                ->all();
            $question_ids = [];
            foreach($student_answers_all as $key => $answer){
                if(in_array($answer->question_id, $questions)){
                    array_push($question_ids, $answer->question_id);
                }
            }
            $question_ids = array_unique($question_ids);
            foreach($question_ids as $id){
                $question = Question::findOne($id);
                if($question->level_id == Level::getLevelId('Лёгкий')){
                    $level = 'student-question-easy';
                }elseif($question->level_id == Level::getLevelId('Средний')){
                    $level = 'student-question-mid';
                }else{
                    $level = 'student-question-hard';
                }
                array_push(
                    $test_list,
                    "<ul class='list-questions mb-4'>".
                    "<li class='list-group-item student-test-question {$level}' style='font-weight:bold'>" . 
                    $question->title .
                    "</li>"
                );
                foreach($question->answers as $answer){
                    $answer_student = StudentAnswer::findOne(['answer_id' => $answer->id, 'user_id' => $user_id, 'attempt' => $student_test->attempt]);
                    if($question->type_id == Type::getTypeId('Ввод ответа от студента')){
                        if($answer_student->true_false){
                            array_push(
                                $test_list,
                                "<li class='list-group-item student-correct-answer'>". 
                                $answer_student->answer_title);    
                        }elseif($answer_student->true_false === 0){
                            array_push(
                                $test_list,
                                "<li class='list-group-item student-incorrect-answer'>". 
                                $answer_student->answer_title);   
                        }else{
                            array_push(
                                $test_list,
                                "<li class='list-group-item'>". 
                                $answer_student->answer_title);   
                        }
                        if(!$answer_student->cheked){
                            array_push($test_list, 
                                '<p style="margin: 5px 0">'.
            
                                    Html::a('Правильный ответ', ['/teacher/student-test/view?id='.Yii::$app->request->get('id').'&student_id=' . $user_id.'&btn=confirm_answer&answer_id='.$answer->id . '&attempt='. $answer_student->attempt ], ['class' => 'btn btn-my-green',   'data-method' => 'post']).
                                
                                    Html::a('Неравильный ответ', ['/teacher/student-test/view?id='.Yii::$app->request->get('id').'&student_id=' . $user_id .'&btn=cancel_answer&answer_id='.$answer->id. '&attempt='. $answer_student->attempt ], ['class' => 'btn btn-my-red ms-1',    'data-method' => 'post']).
            
                                '</p>'
                            );
                        }
                        array_push($test_list,"</li>");
                        
                    }elseif(in_array($answer->id, $student_answers)){
                        if($answer->true_false == 1){
                            array_push(
                                $test_list,
                                "<li class='list-group-item student-correct-answer'>". 
                                $answer->title. 
                                "</li>"
                            );
                        }else{
                            array_push(
                                $test_list,
                                "<li class='list-group-item student-incorrect-answer'>". 
                                $answer->title. 
                                "</li>"
                            );
                        }
                    }else{
                        if($answer->true_false == 1){
                            array_push(
                                $test_list,
                                "<li class='list-group-item student-test-correct-answer '>". 
                                $answer->title. 
                                "</li>"
                            );
                        }else{
                            array_push(
                                $test_list,
                                "<li class='list-group-item student-test-incorrect-answer '>". 
                                $answer->title. 
                                "</li>"
                            );  
                        }
                    }
                }
                array_push(
                    $test_list,
                    "</ul>" 
                );
            }
        }                

        
        $test_list = join($test_list);




        return $this->render('view', [
            'modelStudentTest' => $modelStudentTest,
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'modelTest' => $modelTest,
            'user_id' => $user_id,
            'student_answers' => $student_answers,
            'test_list' => $test_list
        ]);
    }

    /**
     * Creates a new StudentTest model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new StudentTest();

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing StudentTest model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing StudentTest model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the StudentTest model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return StudentTest the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = StudentTest::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
