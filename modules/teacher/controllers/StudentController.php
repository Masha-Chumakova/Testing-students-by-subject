<?php

namespace app\modules\teacher\controllers;

use app\models\User;
use app\models\UserGroup;
use app\models\UserSearch;
use app\models\Group;
use app\controllers\AppTeacherController;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use Yii;
use app\models\StudentTestSearch;
use app\models\StudentTest;
use app\models\Test;
use yii\bootstrap5\Html;
use yii\helpers\VarDumper;
use yii\widgets\ActiveForm;
use yii\web\Response;
/**
 * StudentController implements the CRUD actions for User model.
 */
class StudentController extends AppTeacherController
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
     * Lists all User models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new UserSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single User model.
     * @param int $id ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $modelUser = User::findOne($id);
        $searchModel = new StudentTestSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);
        $dataProvider->query->andWhere([ 'user_id' => $id]);

        $student_group_test_id = StudentTest::find()
                            ->select('group_test_id')
                            ->where(['user_id' => $modelUser->id])
                            ->orderBy(['id' => SORT_DESC])
                            ->all();
        $ids = [];
        $list = [];
        
        foreach($student_group_test_id as $id){
            if(!in_array($id, $ids)){
                array_push($ids, $id);
                $student_test = StudentTest::find()
                            ->where(['user_id' => $modelUser->id, 'group_test_id' => $id])
                            ->orderBy(['id' => SORT_DESC])
                            ->limit(1)
                            ->one();
                // VarDumper::dump($student_test->cheked, 10, true);die;
                if($student_test->cheked === 0){
                    $class = 'link-danger';
                    $span = '<span class="text-muted" style="font-size:12px">&nbsp&nbsp&nbsp необходима проверка</span>';
                }else{
                    $class = 'link-dark';
                    $span = '';
                }
                array_push($list, 
                    Html::a(Test::findOne($student_test->test_id)->title, 
                        '/teacher/student-test/view?id=' . $student_test->id. '&student_id=' . $student_test->user_id,
                        ['class' => $class]) . $span. '<br>');
                }
        }
        
        $list = join($list);

        if(Yii::$app->request->isPost){
            $btn = Yii::$app->request->get('btn');
            if($btn === 'delete_password'){
                if(User::deletePassword($id)){
                    Yii::$app->session->setFlash('success', 'Пароль успешно сброшен');
                    return $this->redirect('view?id=' . $id);
                }
            }
        }

        return $this->render('view', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'modelUser' => $modelUser,
            'list' => $list
        ]);
        
    }

    /**
     * Creates a new User model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $modelUser = new User();
        $modelUserGroup = new UserGroup();
        $groups = Group::getGroupsList();
        if ($this->request->isPost) {
            if (Yii::$app->request->isAjax && $modelUser->load(Yii::$app->request->post())) {
                Yii::$app->response->format = Response::FORMAT_JSON;
                return ActiveForm::validate($modelUser);
            }
            if ( $modelUser->load($this->request->post()) && $modelUserGroup->load($this->request->post())) {
                $modelUser->save(false); 

                $auth = Yii::$app->authManager;
                $student = $auth->getRole('student');
                $auth->assign($student, $modelUser -> id);

                $modelUserGroup->user_id = $modelUser->id;
                $group_id =  Yii::$app->request->post('UserGroup')['group_id'];
                $modelUserGroup -> group_id = Group::getPreviousGroups($group_id);
                $modelUserGroup->save(false);

                $session = Yii::$app->session;
                $session->setFlash('success', 'Студент успешно добавлен');
                return $this->redirect(['view', 'id' => $modelUser->id]);
            }
        } else {
           
            $modelUser->loadDefaultValues();
        }

        return $this->render('create', [
            'modelUser' => $modelUser,
            'modelUserGroup' => $modelUserGroup,
            'groups' => $groups
        ]);
    }

    /**
     * Updates an existing User model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $modelUser = $this->findModel($id);
        $modelUserGroup = UserGroup::findOne(['user_id' => $id]);
        $groups = Group::getGroupsList();
        if ($this->request->isPost) {
            if ( $modelUser->load($this->request->post()) && $modelUserGroup->load($this->request->post())) {

                $modelUser->save(false); 
                $modelUserGroup->user_id = $modelUser->id; 
                $modelUserGroup->save(false);
                $session = Yii::$app->session;
                $session->setFlash('success', 'Информация о студенте успешно изменена');
                return $this->redirect(['view', 'id' => $modelUser->id]);
            }
        }

        return $this->render('update', [
            'modelUser' => $modelUser,
            'modelUserGroup' => $modelUserGroup,
            'groups' => $groups
        ]);
    }

    /**
     * Deletes an existing User model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();
        $session = Yii::$app->session;
        $session->setFlash('success', 'Информация о студенте удалена');
        return $this->redirect(['index']);
    }

    /**
     * Finds the User model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return User the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = User::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
