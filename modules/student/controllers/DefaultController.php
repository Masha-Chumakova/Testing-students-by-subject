<?php
namespace app\modules\student\controllers;

use app\controllers\AppStudentController;
use app\models\Group;
use app\models\GroupTest;
use app\models\UserGroup;
use app\models\Test;
use Yii;
use app\models\StudentTest;
/**
 * Default controller for the `student` module
 */
class DefaultController extends AppStudentController
{
    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
        $group_id = Group::getCurrentGroup(UserGroup::findOne(['user_id' => Yii::$app->user->identity->id]));
        $group = Group::findOne($group_id)->title;
        $tests_count = StudentTest::getTestsCount(Yii::$app->user->identity->id);

        $tests = StudentTest::getPassedTests(Yii::$app->user->identity->id);
        $test_list = [];
        foreach($tests as $group_test_id => $student_test_id){
            $test_title = Test::findOne(GroupTest::findOne($group_test_id)->test_id)->title;
            $test_mark = StudentTest::findOne($student_test_id)->mark;
            if(StudentTest::findOne($student_test_id)->cheked){
                array_push($test_list, 
                '<p class="border-bottom border-1">'.$test_title. ' - ' .'<b style="color:red">'.$test_mark.'</b>' . '</p>'
                );
            }
        }
        $test_list = join($test_list);
        return $this->render('index', 
        [
            'test_list' => $test_list,
            'tests_count' => $tests_count,
            'group' => $group
        ]);
    }
}
