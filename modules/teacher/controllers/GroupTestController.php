<?php

namespace app\modules\teacher\controllers;

use app\models\Deny;
use app\models\Group;
use app\models\GroupTest;
use app\models\GroupTestSearch;
use app\controllers\AppTeacherController;
use app\models\StudentTest;
use app\models\UserGroup;
use DateTimeImmutable;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use Yii;
use yii\bootstrap5\Html;
use yii\helpers\VarDumper;
use app\models\User;
/**
 * GroupTestController implements the CRUD actions for GroupTest model.
 */
class GroupTestController extends AppTeacherController
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
     * Lists all GroupTest models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new GroupTestSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single GroupTest model.
     * @param int $id ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);
        $list = [];
        $model_student_test = [];
        foreach($model->studentTests as $student_test_item){
            $test = StudentTest::find()
                        ->where(['user_id' => $student_test_item->user_id, 'group_test_id' => $id])
                        ->orderBy(['attempt' => SORT_DESC])
                        ->limit(1)
                        ->asArray()
                        ->all();
            $test = $test[0]['id'];
            array_push($model_student_test, $test);    
        }
        $model_student_test = array_unique($model_student_test);

        array_push($list, "<h3 id='mark-5' class='mt-5  border-bottom'>Студенты с оценкой '5'</h3>");
        foreach($model->studentTests as  $student_test_item){
            if(in_array($student_test_item->id, $model_student_test)){
                if($student_test_item->cheked === 0){
                    $class = 'link-danger';
                    $span = '<span class="text-muted" style="font-size:12px">&nbsp&nbsp&nbsp необходима проверка</span>';
                }else{
                    $class = 'link-dark';
                    $span = '';
                }
                if($student_test_item->mark == 5){
                    array_push($list, 
                    Html::a($student_test_item->user->surname . ' ' . $student_test_item->user->name . ' ' . $student_test_item->user->patronymic, 
                        '/teacher/student-test/view?id=' . $student_test_item->id. '&student_id=' . $student_test_item->user_id,
                        ['class' => $class]) . $span. '<br>');
                }
            }
            
        } 

        array_push($list, " <h3 id='mark-4' class='mt-5  border-bottom'>Студенты с оценкой '4'</h3>");

        foreach($model->studentTests as  $student_test_item){
            if(in_array($student_test_item->id, $model_student_test)){
                if($student_test_item->cheked === 0){
                    $class = 'link-danger';
                    $span = '<span class="text-muted" style="font-size:12px">&nbsp&nbsp&nbsp необходима проверка</span>';
                }else{
                    $class = 'link-dark';
                    $span = '';
                }
                if($student_test_item->mark == 4){
                    array_push($list,
                    Html::a( $student_test_item->user->surname . ' ' . $student_test_item->user->name . ' ' . $student_test_item->user->patronymic,
                        '/teacher/student-test/view?id=' . $student_test_item->id. '&student_id=' . $student_test_item->user_id,
                        ['class' => $class]). $span. '<br>') ;
                }
            }
        } 

        array_push($list, " <h3 id='mark-3' class='mt-5  border-bottom'>Студенты с оценкой '3'</h3>");

        foreach($model->studentTests as  $student_test_item){
            if(in_array($student_test_item->id, $model_student_test)){
                if($student_test_item->cheked === 0){
                    $class = 'link-danger';
                    $span = '<span class="text-muted" style="font-size:12px">&nbsp&nbsp&nbsp необходима проверка</span>';
                }else{
                    $class = 'link-dark';
                    $span = '';
                }
                if($student_test_item->mark == 3){
                    array_push($list, 
                    Html::a( $student_test_item->user->surname . ' ' . $student_test_item->user->name . ' ' . $student_test_item->user->patronymic,
                        '/teacher/student-test/view?id=' . $student_test_item->id. '&student_id=' . $student_test_item->user_id,
                        ['class' => $class]). $span. '<br>');
                }
            }
    
        } 

        array_push($list, "<h3 id='mark-2' class='mt-5  border-bottom'>Студенты не сдавшие тест</h3>");

        foreach($model->studentTests as  $student_test_item){
            if(in_array($student_test_item->id, $model_student_test)){
                if($student_test_item->cheked === 0){
                    $class = 'link-danger';
                    $span = '<span class="text-muted" style="font-size:12px">&nbsp&nbsp&nbsp необходима проверка</span>';
                }else{
                    $class = 'link-dark';
                    $span = '';
                }
                if($student_test_item->mark == 2){
                    array_push($list, 
                    Html::a($student_test_item->user->surname . ' ' . $student_test_item->user->name . ' ' . $student_test_item->user->patronymic,
                        '/teacher/student-test/view?id=' . $student_test_item->id. '&student_id=' . $student_test_item->user_id,
                        ['class' => $class]) . $span. '<br>');
                }
            }
        }

        array_push($list, "<h3 id='not-passed' class='mt-5  border-bottom'>Студенты не проходившие тест</h3>");
        $students_unpass = (GroupTest::getTestUnpass($model->group_id, $model->id));
        foreach ( $students_unpass as $student){
            $user = User::findOne($student);
            array_push($list, '<div class="mt-2">'.$user->surname . ' ' . $user->name . ' ' . $user->patronymic.'</div>' );
        }
        // VarDumper::dump((GroupTest::getTestUnpass($model->group_id, $model->id)), 10, true);
            

        $list = join($list);
        $dates = GroupTest::getTestDates($id);
        $dates_arr = ['<ul class="list-group list-group-flush style="background-color:rgba(0, 0, 0, 0) !important;"">'];
        foreach($dates as $date){
            $date = new DateTimeImmutable($date);
            array_push($dates_arr, '<li class="list-group-item" style="background-color:rgba(0, 0, 0, 0) !important;padding-left:0px !important;">'.$date->format('d/m/Y').'</li>');
        }
        array_push($dates_arr, '</ul>');
        $date_str = join($dates_arr);
        $current_marks =  GroupTest::getLatestTestMarks($model_student_test);
        return $this->render('view', [
            'model' => $model,
            'date_str' => $date_str,
            'list' => $list,
            'current_marks' => $current_marks

        ]);
    }

    /**
     * Creates a new GroupTest model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new GroupTest();
        $group_id = Yii::$app->request->get('group_id');
        $group_students = UserGroup::getCurrentStudents(Group::getPreviousGroups($group_id));
        
        if ($this->request->isPost) {
            
            if ($model->load($this->request->post())) {
                
                try{
                    $transaction = Yii::$app->db->beginTransaction();
                    $model->group_id = $group_id;
                    if( $model->save()){
                        if(Deny::createDeny($model->id, $group_students)){
                            $transaction->commit();
                            Yii::$app->session->setFlash('success', 'Тест добавлен.');   
                            return $this->redirect(['view', 'id' => $model->id]);
                        }
                    }
                   
                }catch(\Throwable $e){
                    
                    $transaction->rollBack();
                    Yii::$app->session->setFlash('error', 'Ошибка при добавлении теста.');
                }
                
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
            'group_id' => $group_id
        ]);
    }

    /**
     * Updates an existing GroupTest model.
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
     * Deletes an existing GroupTest model.
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
     * Finds the GroupTest model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return GroupTest the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = GroupTest::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
