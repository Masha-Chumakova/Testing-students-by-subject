<?php

namespace app\models;

use Yii;
use yii\helpers\VarDumper;

/**
 * This is the model class for table "student_test".
 *
 * @property int $id
 * @property int $points
 * @property int $mark
 * @property int $test_id
 * @property int $user_id
 * @property int $group_test_id
 * @property int $cheked
 * @property int $date
 *
 * @property GroupTest $groupTest
 * @property Test $test
 * @property User $user
 */
class StudentTest extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'student_test';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['points', 'mark', 'test_id', 'user_id', 'group_test_id', 'cheked'], 'required'],
            [['points', 'mark', 'test_id', 'user_id', 'group_test_id', 'cheked', 'attempt'], 'integer'],
            [['ip'], 'string', 'max' => 255],
            [['group_test_id'], 'exist', 'skipOnError' => true, 'targetClass' => GroupTest::class, 'targetAttribute' => ['group_test_id' => 'id']],
            [['test_id'], 'exist', 'skipOnError' => true, 'targetClass' => Test::class, 'targetAttribute' => ['test_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'points' => 'Points',
            'mark' => 'Mark',
            'test_id' => 'Test ID',
            'user_id' => 'User ID',
            'group_test_id' => 'Group Test ID',
            'cheked' => 'Cheked',
            'date' => 'Дата прохождения теста',
            'attempt' => 'Попытка',
            'ip' => 'IP'
        ];
    }

    /**
     * Gets query for [[GroupTest]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getGroupTest()
    {
        return $this->hasOne(GroupTest::class, ['id' => 'group_test_id']);
    }

    /**
     * Gets query for [[Test]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTest()
    {
        return $this->hasOne(Test::class, ['id' => 'test_id']);
    }

    /**
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }

    public static function createStudentTest($test_id, $group_test_id, $attempt, $user_id)
    {
        $modelStudentTest = new StudentTest();
        $modelStudentTest -> points = static::getStudentTestPoints($test_id, $group_test_id, $attempt, $user_id );
        $modelStudentTest -> mark = static::getStudentTestMark($test_id, $group_test_id, $attempt, $user_id);
        $modelStudentTest -> test_id = $test_id;
        $modelStudentTest -> user_id = Yii::$app->user->identity->id;
        $modelStudentTest -> group_test_id = $group_test_id;
        $modelStudentTest -> cheked = 0;
        $modelStudentTest -> attempt = $attempt;
        $modelStudentTest -> date = date('Y-m-d');

        $test_questions = Test::getTestQuestionsList($group_test_id);

        if($modelStudentTest -> save()){
            $deny = Deny::find()->where(['user_id' => Yii::$app->user->identity->id, 'group_test_id' => $group_test_id])->one();
            $deny -> true_false = 0;
            $res = $deny->save();
        }
        return $res;  
    }


    public static function getIsChecked($student_id, $group_test_id, $attempt)
    {
        $current_student_test = static::findOne(['user_id' => $student_id, 'group_test_id' => $group_test_id, 'attempt' => $attempt]);
        $test_id = GroupTest::findOne($group_test_id)->test_id;
        $questions = Question::find()->where(['test_id' => $test_id])->all();
        $counter = 0;
        foreach($questions as $question){
            $student_answer = StudentAnswer::findOne(['question_id' => $question['id'], 'user_id' => $student_id, 'attempt' => $attempt]);
            if($student_answer){
                if($student_answer->cheked === 0){
                    $counter++;
                }
            }
            
        }
        if($counter != 0){
            $current_student_test->cheked = 0;
        }else{
            $current_student_test->cheked = 1;
        }
        if($current_student_test->save()){
            return $current_student_test->cheked;
        }
        
    }

    public static function getStudentTestPoints($test_id, $group_test_id, $attempt, $user_id)
    {
        // $user = User::findOne(Yii::$app->user->identity->id);
        $test = Test::findOne($test_id);
        $points = 0;
        $questions = Question::find() -> where(['test_id' => $test_id]) -> all();
        $answers = [];
        foreach($questions as $question){
            $student_answers = StudentAnswer::find() 
                                    -> where(['user_id' => $user_id, 'attempt' => $attempt, 'question_id' => $question['id']])
                                    -> all();
            $i = 0;
            foreach($student_answers  as $answer ){
                $answers[$answer['question_id']][$i] = $answer['answer_id'];
                $i++;
            }
        }  
        foreach($answers as $question_id => $answer){
            if(Question::findOne($question_id)->type_id == Type::getTypeId('Ввод ответа от студента')){
                $student_answer = StudentAnswer::findOne(['question_id' => $question_id, 'user_id' => $user_id, 'attempt' => $attempt]);
                $res = $student_answer -> true_false;
            }else{
                $res = StudentAnswer::getIsCorrectAnswer($question_id, $answer);
            }
            if($res){
                $question_level_id = Question::findOne($question_id)->level_id;
                if($question_level_id == Level::getLevelId('Лёгкий')){
                    $points += 1;
                }elseif($question_level_id == Level::getLevelId('Средний')){
                    $points += 2;
                }elseif($question_level_id == Level::getLevelId('Сложный')){
                    $points += 3;
                }
            }
            
        }
        return $points;
    }

    public static function getStudentTestMark($test_id, $group_test_id, $attempt, $user_id)
    {
        $student_points = static::getStudentTestPoints($test_id, $group_test_id, $attempt, $user_id);
        $max_points = Test::findOne($test_id) -> max_points;

        $percent =  $student_points * 100 / $max_points;
        if($percent >= 80){
            $mark = 5;
        }elseif($percent < 80 && $percent >= 60){
            $mark = 4;
        }elseif($percent < 60 && $percent >= 40){
            $mark = 3;
        }else{
            $mark = 2;
        }
        return $mark;
    }

    public static function changeStudentTest($test_id, $group_test_id, $attempt, $student_id)
    {
        $student_test = StudentTest::findOne(['group_test_id' => $group_test_id, 'attempt' => $attempt, 'user_id' => $student_id]);
        $student_test -> points = static::getStudentTestPoints($test_id, $group_test_id, $attempt, $student_id);
        $student_test -> mark = static::getStudentTestMark($test_id, $group_test_id, $attempt, $student_id);
        return $student_test -> save();
    }

    public static function getTestsCount($user_id)
    {
        $res = static::find()->where(['user_id' => $user_id])->select('group_test_id')->column();
        $res = count(array_unique($res));
        return $res;
    }

    public static function getPassedTests($user_id)
    {
        $res = static::find()
                        ->where(['user_id' => $user_id])
                        ->indexBy('group_test_id')
                        ->column();
        $res = array_unique($res);
        return $res;
    }

    public static function getUncheckedTests()
    {
        return static::find()->where(['cheked' => 0])->asArray()->all();
    }

}
