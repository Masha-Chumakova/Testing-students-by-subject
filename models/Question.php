<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "question".
 *
 * @property int $id
 * @property string $title
 * @property int $points
 * @property int $type_id
 * @property int $level_id
 * @property int $test_id
 * @property string|null $img
 *
 * @property Answer[] $answers
 * @property Level $level
 * @property StudentAnswer[] $studentAnswers
 * @property Test $test
 * @property Type $type
 */
class Question extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'question';
    }
    
    public $imageFile;
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title', 'points', 'type_id', 'level_id', 'test_id'], 'required'],
            [['points', 'type_id', 'level_id', 'test_id'], 'integer'],
            [['title', 'img'], 'string', 'max' => 255],
            [['level_id'], 'exist', 'skipOnError' => true, 'targetClass' => Level::class, 'targetAttribute' => ['level_id' => 'id']],
            [['test_id'], 'exist', 'skipOnError' => true, 'targetClass' => Test::class, 'targetAttribute' => ['test_id' => 'id']],
            [['type_id'], 'exist', 'skipOnError' => true, 'targetClass' => Type::class, 'targetAttribute' => ['type_id' => 'id']],
            [['imageFile'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Вопрос',
            'points' => 'Баллы за вопрос',
            'type_id' => 'Тип вопроса',
            'level_id' => 'Сложность вопроса',
            'test_id' => 'Test ID',
            'img' => 'Изображение',
        ];
    }

    /**
     * Gets query for [[Answers]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAnswers()
    {
        return $this->hasMany(Answer::class, ['question_id' => 'id']);
    }

    /**
     * Gets query for [[Level]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getLevel()
    {
        return $this->hasOne(Level::class, ['id' => 'level_id']);
    }

    /**
     * Gets query for [[StudentAnswers]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getStudentAnswers()
    {
        return $this->hasMany(StudentAnswer::class, ['question_id' => 'id']);
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
     * Gets query for [[Type]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getType()
    {
        return $this->hasOne(Type::class, ['id' => 'type_id']);
    }
    public static function getQuestionsOfTest($test_id)
    {
        $qa = [];
        $questions = static::find() -> where(['test_id' => $test_id])->asArray()->all();
        
        foreach($questions as $key => $question){
            $question_id = $question['id'];
            $question_title = $question['title'];
            $answer = Answer::getAnswersOfQuestion($question_id);
            for( $i = 0; $i < count($answer); $i++){
                $qa[$question_title][$i] = $answer[$i]['title'];
            }
        }
        
        return $qa;
    }

    public function upload()
    {
        if ($this->validate()) {
            $fileName = Yii::$app->user->identity->id . '_' . time() . '_' . Yii::$app->security->generateRandomString(10)  .'.' .$this->imageFile->extension;
            $this->imageFile->saveAs(Yii::getAlias('@app') .'/web/question-img/' . $fileName);
            $this -> img = '/web/question-img/' . $fileName;
            return true;
        } else {
            return false;
        }
    }
}
