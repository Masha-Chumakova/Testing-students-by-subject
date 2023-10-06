<?php

namespace app\modules\student\controllers;

use app\models\Answer;
use app\models\Deny;
use app\models\StudentTest;
use app\models\StudentTestSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\GroupTest;
use app\models\Level;
use app\models\Question;
use app\models\StudentAnswer;
use app\models\Test;
use app\models\Type;
use Yii;
use yii\helpers\VarDumper;

/**
 * StudentTestController implements the CRUD actions for StudentTest model.
 */
class TestController extends Controller
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
     * Displays a single StudentTest model.
     * @param int $id ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionIndex()
    {
        
        $group_test_id = Yii::$app->request->post('id');
        $session = Yii::$app->session;
        $current_question = Yii::$app->request->post('question');
        $current_question = empty($current_question) ? 1 : $current_question;

        $attempt = StudentAnswer::getLastAttempt($group_test_id) + 1;

        $modelStudentAnswer = new StudentAnswer();
        
        if ($this->request->isPost) {
            if(isset($_POST['StudentAnswer'])){ 
                if(is_array($_POST['StudentAnswer']['answer_id'])){
                    foreach($_POST['StudentAnswer']['answer_id'] as $answer_id){
                        $modelStudentAnswer = new StudentAnswer();
                        $modelStudentAnswer->user_id = Yii::$app->user->identity->id;
                        $modelStudentAnswer->question_id = $_POST['StudentAnswer']['question_id'];
                        $modelStudentAnswer->answer_id = $answer_id;     
                        $modelStudentAnswer -> attempt = $attempt;                   
                        $modelStudentAnswer->save();
                    }
                }else{
                    $modelStudentAnswer = new StudentAnswer();
                    $modelStudentAnswer->user_id = Yii::$app->user->identity->id;
                    $modelStudentAnswer->question_id = $_POST['StudentAnswer']['question_id'];
                    $modelStudentAnswer->load($this->request->post());
                    $modelStudentAnswer->save();
                }
                $current_question++;    
            }  
        } 
        if ($current_question > 1) {
            $questions_square = $session->get('questions_square'); 
            $questions_square[$current_question - 1] = 'passed';
            $questions_square[$current_question] = 'current_question';
            $passed_questions = $session->get('passed_questions');
            $passed_questions[$_POST['StudentAnswer']['question_id']] = $_POST['StudentAnswer']['answer_id'];

            
        }else{
            $questions_square = Test::getTestQuestionsList($group_test_id);            
            $questions_square[1] = 'current_question';
            $current_question = 1; 
            $passed_questions = [];
        }
        $session->set('passed_questions', $passed_questions);
        
        $test_id = GroupTest::findOne([$group_test_id])->test_id;
       
        $question_id = Test::getNextQuestion($session->get('passed_questions'), $test_id);

        if(!$question_id){
            if(StudentTest::createStudentTest($test_id, $group_test_id, $attempt, Yii::$app->user->identity->id)){
                if(GroupTest::changeGroupTest($group_test_id)){
                    StudentTest::getIsChecked(Yii::$app->user->identity->id, $group_test_id, $attempt);
                    return $this->redirect('/student/group-test');
                }
            }else{
                Yii::$app->session->setFlash('error', 'Ошибка при отправлении теста');
                return true;
            }
            
        }

        $question = Question::findOne($question_id);
        $answers = Answer::find()
        ->select('title')
        ->where(['question_id' => $question->id])
        ->indexBy('id')
        ->column();
        
        $session->set('questions_square',  $questions_square);

        $questions_str = join(array_map(fn($question_key, $question) => "<div class='btn  btn-my-green-outline {$question} question-square text-center'>{$question_key}</div>", array_keys($questions_square), $questions_square));
        
        $test_title = Test::findOne($test_id)->title;

        return $this->render('index',
        [
            'group_test_id' => $group_test_id,
            'question' => $question,
            'modelStudentAnswer' => $modelStudentAnswer,
            'answers' => $answers,
            'questions_str' => $questions_str,
            'current_question' => $current_question,
            'test_title' => $test_title,
            'attempt' => $attempt
        ]);
            
    }

   
}
