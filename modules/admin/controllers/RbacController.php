<?php

namespace app\modules\admin\controllers;

use yii\web\Controller;
use Yii;

class RbacController extends Controller
{
    public function actionInit(){
        $auth = Yii::$app->authManager;

        $canTeacher = $auth->createPermission('canTeacher');
        $auth->add($canTeacher);

        $canAdmin = $auth->createPermission('canAdmin');
        $auth->add($canAdmin);

        $canStudent = $auth->createPermission('canStudent');
        $auth->add($canStudent);

        $admin = $auth->createRole('admin');
        $auth->add($admin);
        $auth->addChild($admin, $canAdmin);

        $teacher = $auth->createRole('teacher');
        $auth->add($teacher);
        $auth->addChild($teacher, $canTeacher);

        $student = $auth->createRole('student');
        $auth->add($student);
        $auth->addChild($student, $canStudent);

        $auth->assign($admin, 1);
        $auth->assign($student, 31);
        
    }
    
}



