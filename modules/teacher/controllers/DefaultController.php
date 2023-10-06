<?php

namespace app\modules\teacher\controllers;

use app\controllers\AppTeacherController;
use app\models\Group;
use app\models\GroupTest;
use app\models\StudentTest;
use app\models\Test;
use app\models\User;
use app\models\UserGroup;
use yii\helpers\VarDumper;

/**
 * Default controller for the `teacher` module
 */
class DefaultController extends AppTeacherController
{
    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
        $all_tests = ["<table class='table table-hover'>" .
            "<thead >
                <tr class='table-success'>
                    <th scope='col'>Тест</th>
                    <th scope='col'>Группа</th>
                    <th scope='col'>Дата</th>
                    <th scope='col'>Средний бал</th>
                </tr>
            </thead>
            <tbody>"
        ];
        foreach(UserGroup::getTeachersGroups(\Yii::$app->user->identity->id) as $group){
            foreach(GroupTest::getTestStatistic($group['group_id']) as $group_test) {
                $test_title = Test::findOne($group_test['test_id'])->title;
                $test_date = GroupTest::getTestDates($group_test['id']);
                $test_group = Group::findOne($group_test['group_id'])->title;
                if($group_test['avg_points']){
                    $test_avg = round($group_test['avg_points'], 2);
                }
                $test_max = Test::findOne($group_test['test_id'])->max_points;
                array_push($all_tests,
                    "<tr>
                              <td>".$test_title ."</td>
                              <td>".$test_group."</td>
                              <td>".end($test_date) ."</td>
                              <td><b>".$test_avg."</b>/".$test_max."</td>
                            </tr>");
            }
        }
        array_push($all_tests, "</tbody></table>");
        $all_tests = join($all_tests);

        $unchek_tests = [];
        foreach (StudentTest::getUncheckedTests() as $test) {
            $student_id = $test['user_id'];
            $id = $test['id'];
            $test_title = Test::findOne($test['test_id'])->title;
            $fio = User::getFIO($student_id);
            array_push($unchek_tests,
                "<p class='border-bottom mt-3'><a href='/teacher/student-test/view?id=".$id ."&student_id=".$student_id."'>".
                $fio . " - " . $test_title
                ."</a></p>");
        };
        $unchek_tests = join($unchek_tests);
        return $this->render('index',
        [
            'all_tests' => $all_tests,
            'unchek_tests' => $unchek_tests
        ]);
    }
}
