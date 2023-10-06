<?php

namespace app\modules\teacher\controllers;

use app\models\Group;
use app\models\GroupSerch;
use app\controllers\AppTeacherController;
use app\models\UserGroup;
use yii\helpers\VarDumper;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\GroupTestSearch;
use Yii;
use app\models\User;
/**
 * GroupController implements the CRUD actions for Group model.
 */
class GroupController extends AppTeacherController
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
     * Lists all Group models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new GroupSerch();
        $groups = Group::find()->select(['title'])->indexBy('id')->column();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'groups' => $groups
        ]);
    }

    /**
     * Displays a single Group model.
     * @param int $id ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $modelGroup = Group::findOne($id);
        $next_group = Group::findOne(['previous_group_id' => $id]);
        $searchModel = new GroupTestSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);
        $dataProvider->query->andWhere([ 'group_id' => $id]);
        $first_previous_group = Group::getPreviousGroups($id);

        $marks = Group::getMarks($id);
        $marks_percent = Group::getMarksPercent($id);
        $tests_count = Group::getTestsCount($id);
        $students = UserGroup::getUsersOfGroup(Group::getPreviousGroups($id), $id);
        if(Yii::$app->request->isPost){
            $btn = Yii::$app->request->get('btn');
            if($btn === 'document'){
                $phpWord = new \PhpOffice\PhpWord\PhpWord();
                $section = $phpWord->addSection();
                $title = $modelGroup->title;
                $phpWord->addTitleStyle(1, array('size' => 16, 'bold' => true));
                $section->addTitle('Группа: ' . $title, 1);
                $counter = 1;
                foreach($students as $id => $fio){
                    $login = User::findOne($id)->login;
                    $password = User::findOne($id)->password_2;
                    $first_line = $counter . '. ' . $fio;
                    $second_line = 'Логин: ' . $login ;
                    $third_line = 'Пароль: ' . $password;
                    $section->addText($first_line);
                    $section->addTextBreak(0);
                    $section->addText($second_line);
                    $section->addTextBreak(0);
                    $section->addText($third_line);
                    $section->addTextBreak(1);
                    $counter++;
                }
                
                $fileName = 'Группа: ' . $title;
                header("Content-Description: File Transfer");
                header('Content-Disposition: attachment; filename=' . $fileName . '.docx"');
                header('Content-Type: application/msword');
                header('Content-Transfer-Encoding: binary');
                header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
                $objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord);
                $objWriter->save('php://output');
            }exit;
        }
        

        return $this->render('view', [
            'modelGroup' => $modelGroup,
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'next_group' => $next_group,
            'first_previous_group' => $first_previous_group,
            'marks' => $marks,
            'tests_count' => $tests_count,
            'marks_percent' => $marks_percent
        ]);
    }

    /**
     * Creates a new Group model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    // public function actionCreate()
    // {
    //     $model = new Group();

    //     if ($this->request->isPost) {
    //         if ($model->load($this->request->post()) && $model->save()) {
    //             return $this->redirect(['view', 'id' => $model->id]);
    //         }
    //     } else {
    //         $model->loadDefaultValues();
    //     }

    //     return $this->render('create', [
    //         'model' => $model,
    //     ]);
    // }

    /**
     * Updates an existing Group model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    // public function actionUpdate($id)
    // {
    //     $model = $this->findModel($id);

    //     if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
    //         return $this->redirect(['view', 'id' => $model->id]);
    //     }

    //     return $this->render('update', [
    //         'model' => $model,
    //     ]);
    // }

    /**
     * Deletes an existing Group model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    // public function actionDelete($id)
    // {
    //     $this->findModel($id)->delete();

    //     return $this->redirect(['index']);
    // }

    /**
     * Finds the Group model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Group the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Group::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
