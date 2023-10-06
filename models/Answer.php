<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "answer".
 *
 * @property int $id
 * @property int $title
 * @property int $true_false
 * @property int $question_id
 *
 * @property Question $question
 * @property StudentAnswer[] $studentAnswers
 */
class Answer extends \yii\db\ActiveRecord
{
    const SKIP_ANSWER = 'skip_answer';
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'answer';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        
        return [
            [['title'], 'required', 'except' => self::SKIP_ANSWER],
            [ ['true_false', 'question_id'], 'integer'],
            [['title'], 'string', 'max' => 255],
            [['question_id'], 'exist', 'skipOnError' => true, 'targetClass' => Question::class, 'targetAttribute' => ['question_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'Ответ',
            'title' => 'Ответ',
            'true_false' => 'True False',
            'question_id' => 'Question ID',
        ];
    }

    /**
     * Gets query for [[Question]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getQuestion()
    {
        return $this->hasOne(Question::class, ['id' => 'question_id']);
    }

    /**
     * Gets query for [[StudentAnswers]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getStudentAnswers()
    {
        return $this->hasMany(StudentAnswer::class, ['answer_id' => 'id']);
    }

    public static function getAnswersOfQuestion($question_id)
    {
        return static::find() -> where(['question_id' => $question_id])->asArray()->all();
    }

    
}
