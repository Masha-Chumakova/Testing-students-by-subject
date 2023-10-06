<?php

namespace app\models;

use Yii;
use yii\helpers\VarDumper;

/**
 * This is the model class for table "test".
 *
 * @property int $id
 * @property string $title
 * @property int $max_points
 *
 * @property GroupTest[] $groupTests
 * @property Question[] $questions
 * @property StudentTest[] $studentTests
 */
class Test extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'test';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title', 'questions_count'], 'required'],
            [['max_points', 'questions_count'], 'integer'],
            [['title'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Название теста',
            'max_points' => 'Максимальное колличество баллов за тест',
            'questions_count' => 'Количество вопросов для прохождения теста'
        ];
    }

    /**
     * Gets query for [[GroupTests]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getGroupTests()
    {
        return $this->hasMany(GroupTest::class, ['test_id' => 'id']);
    }

    /**
     * Gets query for [[Questions]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getQuestions()
    {
        return $this->hasMany(Question::class, ['test_id' => 'id']);
    }

    /**
     * Gets query for [[StudentTests]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getStudentTests()
    {
        return $this->hasMany(StudentTest::class, ['test_id' => 'id']);
    }

    public static function getTestsList()
    {
        return static::find() -> select('title') -> indexBy('id')->column();
    }

    public static function getTestQuestionsList($group_test_id)
    {
        $questions_list = [];
        $group_test = GroupTest::findOne($group_test_id)->test_id;
        $question = Test::findOne($group_test) -> questions_count;
        for($i = 0; $i < $question; $i++){
            $questions_list[$i+1] = 'unpassed';
        }

        return $questions_list;
    }

    public static function getQuestionsByLevel($test_id, $level_title)
    {
        return static::findOne($test_id)->getQuestions()->where(['level_id' => Level::getLevelId($level_title)])->column();
    }



    public static function getResault($test_id, $passed_questions, $title)
    {
        $questions = static::getQuestionsByLevel($test_id, $title);
        if($questions){
            foreach($questions as $key => $question){
                $res = array_key_exists($question, $passed_questions);
                    if(!$res){ //res = false
                        return $questions[$key]; // question_id
                    }
            };
            
            if($res){ // res = true
                return !$res;  // false  
            }
        }
        
    }

    public static function getQuestionId($first, $second, $third, $test_id, $passed_questions)
    {
        $res = static::getResault($test_id, $passed_questions, $first );
        if(!$res){
            $res = static::getResault($test_id, $passed_questions, $second);
            if(!$res){
                $res = static::getResault($test_id, $passed_questions, $third);
            };
        };
        return $res;
    }

    public static function getNextQuestion($passed_questions, $test_id)
    {
        if($passed_questions){
            if(Question::findOne(array_key_last($passed_questions))->type_id == Type::getTypeId('Ввод ответа от студента')){
                $answer = true;
            }else{
                $answer = StudentAnswer::getIsCorrectAnswer(array_key_last($passed_questions), end($passed_questions));
            }   
        }
        
        $question_count = Test::findOne($test_id) -> questions_count;
        
        if(count($passed_questions) == $question_count){
            return false;
        }

        if($passed_questions){
            $current_question_level_id = Question::findOne(array_key_last($passed_questions))->level_id;
            $previous_question_id = array_keys(array_slice($passed_questions, -2, 1, true));
            $previous_question_level_id = Question::findOne($previous_question_id)->level_id;
        }

        foreach($passed_questions as $question_id => $answers){
            if($question_id == (end($previous_question_id))){
                $previous_answers = $answers;
            }
        }

        if(count($passed_questions) < 2){
            $questions = static::getQuestionsByLevel($test_id, 'Средний');
            $res = true;
            while($res){
                $rand_question = $questions[array_rand($questions, 1)];
                $res = array_key_exists($rand_question, $passed_questions);
            }
            return $rand_question;
        }

        if(count($passed_questions) == 2 ){
            $i = 0;
            foreach($passed_questions as $question_id => $answers){
                if($i == 0){
                    $first_question_id = $question_id;
                    $first_questions = $answers;
                }
                if($i == 1){
                    $second_question_id = $question_id;
                    $second_questions = $answers;
                }
                $i++;
            }
            if(StudentAnswer::getIsCorrectAnswer($first_question_id,  $first_questions) &&  StudentAnswer::getIsCorrectAnswer($second_question_id, $second_questions)){

                return $res = static::getQuestionId('Сложный', 'Средний', 'Лёгкий', $test_id, $passed_questions);

            }else{

                return $res = static::getQuestionId('Лёгкий', 'Средний', 'Сложный', $test_id, $passed_questions);
            }
        }else{
            if($answer){
                if($previous_question_level_id != $current_question_level_id){
                    $title = Level::findOne($current_question_level_id)->title;
                    if($title == 'Сложный'){

                        $res = static::getQuestionId('Сложный', 'Средний', 'Лёгкий', $test_id, $passed_questions);

                    }elseif($title == 'Средний'){

                        $res = static::getQuestionId('Средний', 'Лёгкий', 'Сложный', $test_id, $passed_questions);
                        
                    }elseif($title == 'Лёгкий'){
                        $res = static::getQuestionId('Лёгкий', 'Средний', 'Сложный', $test_id, $passed_questions);
                    }
                    return $res;
                }else{
                    if( StudentAnswer::getIsCorrectAnswer($previous_question_id, $previous_answers) ){
                        if($current_question_level_id == Level::getLevelId('Сложный') || $current_question_level_id == Level::getLevelId('Средний')){
                            return $res = static::getQuestionId('Сложный', 'Средний', 'Лёгкий', $test_id, $passed_questions);
                        }else{
                            return $res = static::getQuestionId('Средний', 'Сложный', 'Лёгкий', $test_id, $passed_questions);
                        }
                    }else{
                        return $res = static::getQuestionId('Лёгкий', 'Средний', 'Сложный', $test_id, $passed_questions);
                    }
                }
            }else{
                if($current_question_level_id == Level::getLevelId('Средний') || $current_question_level_id == Level::getLevelId('Лёгкий')){
                    return $res = static::getQuestionId('Лёгкий', 'Средний', 'Сложный', $test_id, $passed_questions);
                }else{
                    return $res = static::getQuestionId('Средний', 'Лёгкий', 'Сложный', $test_id, $passed_questions);
                }
            }
        }  
        
    }


    public static function getTestMaxPoints($id)
    {
        $questions_count = Test::findOne($id)->questions_count;
        
        $mid_level_id = Level::getLevelId('Средний');
        $hard_level_id = Level::getLevelId('Сложный');

        $mid_questions = Question::find()
                            ->where(['test_id' => $id, 'level_id' => $mid_level_id])
                            ->count();
        $hard_questions = Question::find()
                            ->where(['test_id' => $id, 'level_id' => $hard_level_id])
                            ->count();

        if($hard_questions >= $questions_count - 2){
            $max_points = $questions_count * 3 - 2;
        }elseif($hard_questions < $questions_count - 2 && $mid_questions >= $questions_count - $hard_questions){
            $max_points = $questions_count * 2 + $hard_questions;
        }elseif($hard_questions < $questions_count - 2 && $mid_questions < $questions_count - $hard_questions ){
            $max_points = $hard_questions * 2 + $mid_questions + $questions_count;
        }
       
        return $max_points;
    }
}
