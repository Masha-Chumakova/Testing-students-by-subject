<?php

namespace app\models;

use Yii;
use yii\helpers\VarDumper;
use app\models\UserGroup;

/**
 * This is the model class for table "group_test".
 *
 * @property int $id
 * @property string $date
 * @property float $avg_points
 * @property int $val_5
 * @property int $val_4
 * @property int $val_3
 * @property int $fails
 * @property int $group_id
 * @property int $test_id
 *
 * @property Deny[] $denies
 * @property Group $group
 * @property StudentTest[] $studentTests
 * @property Test $test
 */
class GroupTest extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'group_test';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['group_id', 'test_id'], 'required'],
            [['date'], 'safe'],
            [['avg_points'], 'number'],
            [['val_5', 'val_4', 'val_3', 'fails', 'group_id', 'test_id'], 'integer'],
            [['group_id'], 'exist', 'skipOnError' => true, 'targetClass' => Group::class, 'targetAttribute' => ['group_id' => 'id']],
            [['test_id'], 'exist', 'skipOnError' => true, 'targetClass' => Test::class, 'targetAttribute' => ['test_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'date' => 'Дата проведения теста',
            'avg_points' => 'Среднее количество баллов за тест',
            'val_5' => 'Val 5',
            'val_4' => 'Val 4',
            'val_3' => 'Val 3',
            'fails' => 'Fails',
            'group_id' => 'Группа',
            'test_id' => 'Тест',
        ];
    }

    /**
     * Gets query for [[Denies]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getDenies()
    {
        return $this->hasMany(Deny::class, ['group_test_id' => 'id']);
    }

    /**
     * Gets query for [[Group]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getGroup()
    {
        return $this->hasOne(Group::class, ['id' => 'group_id']);
    }

    /**
     * Gets query for [[StudentTests]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getStudentTests()
    {
        return $this->hasMany(StudentTest::class, ['group_test_id' => 'id']);
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
    public static function getGroupTestIdByGroupId($group_id)
    {
        return static::findOne(['group_id' => $group_id]) -> id;
    }

    public static function changeGroupTest($group_test_id)
    {
        $test = static::findOne($group_test_id);
        $students = StudentTest::find()->where(['group_test_id' => $group_test_id]);
        $student_count = $students->count();
        $all_points = 0;
        $students_column = StudentTest::find()
                                    ->where(['group_test_id' => $group_test_id])
                                    ->all();
        foreach($students_column as $student){
            $all_points += $student->points;
        }
        $test->avg_points = $all_points/$student_count;
        $current_student = StudentTest::findOne(['group_test_id' => $group_test_id, 'user_id' => Yii::$app->user->identity->id]);
        $mark = $current_student->mark;
        if($mark == 5){
            $test->val_5++;
        }elseif($mark == 4){
            $test->val_4++;
        }elseif($mark == 3){
            $test->val_3++;
        }elseif($mark == 2){
            $test->fails++;
        }
        return $test->save();
    }

    public static function getTestDates($group_test_id)
    {
        $student_tests = StudentTest::find()
                            ->select('date')
                            ->where(['group_test_id' => $group_test_id])
                            ->asArray()
                            ->all();
        $dates = [];
        foreach($student_tests as $date){
            array_push($dates, $date['date']);
        } 
        return array_unique($dates);
    }

    public static function getTestUnpass($group_id, $group_test_id)
    {
        $first_group = Group::getPreviousGroups($group_id);
        $students = UserGroup::getCurrentStudents($first_group);
        $test = static::findOne($group_test_id);
        $ids = [];
        foreach($students as $id => $student){
            $student_test = StudentTest::findOne(['user_id' => $id, 'group_test_id' => $group_test_id]);
            if(!$student_test){
                array_push($ids, $id);
            }
        }
        return $ids;
    }

    public static function getMarksPercent($group_test_id)
    {
        $group_test = GroupTest::findOne($group_test_id);
        $all_marks_count = 0;
        $all_marks_count += $group_test->val_5;
        $all_marks_count += $group_test->val_4;
        $all_marks_count += $group_test->val_3;
        $all_marks_count += $group_test->fails;

        $marks_percent = [];
        // $marks_percent[5] = 
        // return $all_marks_count;
    }

    public static function getLatestTestMarks( $student_test_ids)
    {
        $marks = [];
        $marks[5] = 0;
        $marks[4] = 0;
        $marks[3] = 0;
        $marks[2] = 0;
        foreach($student_test_ids as $student_test_id)
        {
            $student_mark = StudentTest::findOne($student_test_id) -> mark;
            $marks[$student_mark]++;  
        }
        return $marks;
    }

    public static function getTestStatistic($group_id)
    {
        return static::find()->where(['group_id' => $group_id])->asArray()->all();

    }

}
