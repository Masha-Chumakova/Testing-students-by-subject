<?php

namespace app\models;

use Yii;
use yii\helpers\VarDumper;

/**
 * This is the model class for table "group".
 *
 * @property int $id
 * @property string $title
 * @property string $year
 * @property int $course
 * @property int|null $previous_group_id
 *
 * @property GroupTest[] $groupTests
 * @property Group[] $groups
 * @property Group $previousGroup
 * @property UserGroup[] $userGroups
 */
class Group extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'group';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title', 'year', 'course'], 'required'],
            [['year'], 'safe'],
            [['course', 'previous_group_id'], 'integer'],
            [['title'], 'string', 'max' => 255],
            [['previous_group_id'], 'exist', 'skipOnError' => true, 'targetClass' => Group::class, 'targetAttribute' => ['previous_group_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Группа',
            'year' => 'Год',
            'course' => 'Курс',
            'previous_group_id' => 'Предыдущая группа',
        ];
    }

    /**
     * Gets query for [[GroupTests]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getGroupTests()
    {
        return $this->hasMany(GroupTest::class, ['group_id' => 'id']);
    }

    /**
     * Gets query for [[Groups]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getGroups()
    {
        return $this->hasMany(Group::class, ['previous_group_id' => 'id']);
    }

    /**
     * Gets query for [[PreviousGroup]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPreviousGroup()
    {
        return $this->hasOne(Group::class, ['id' => 'previous_group_id']);
    }

    /**
     * Gets query for [[UserGroups]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUserGroups()
    {
        return $this->hasMany(UserGroup::class, ['group_id' => 'id']);
    }
    public static function getGroupsList()
    {
        return static::find()->select('title')->indexBy('id')->column();
    }

    public static function getGroupTitle($id)
    {
        return static::findOne(['id' => $id])->title;
    }

    public static function getGroupId($title)
    {
        return static::findOne(['title' => $title])->id;
    }

    public static function getGroupIds()
    {
        return  static::find()->select('title')->indexBy('id')->column();
    }


    public static function getPreviousGroups($id)
    {
        $previous_groups = [];
        $current_group = static::findOne($id);
        $q = $current_group -> id;
        for($i = 4; $i > 0; $i--){
            if( $prev_id = static::findOne($q)){
                if($q = $prev_id -> previous_group_id){
                    array_push($previous_groups, $q);
                }
            }
        }
        if(!empty($previous_groups)){
            return end($previous_groups);
        }else{
            return $id;
        }
    }

    public static function getCurrentGroup($first_group_id)
    {
        $next_groups = [];
        $next_group = Group::findOne(['previous_group_id' => $first_group_id]);
        if($next_group){
            $next_group_id = $next_group -> id;
            for($i = 1; $i < 5; $i++){
                if($next_group = Group::findOne(['previous_group_id' => $next_group_id])){
                    if($next_group_id = $next_group ->id){
                        array_push($next_groups, $next_group_id);
                    }
                    
                }else{
                    return $next_group_id;
                }
                
            }
            return end($next_groups);
        }else{
            return $first_group_id;
        }
        
        
    }

    public static function getMarks($group_id)
    {   
        $group_test = GroupTest::find()
                                ->where(['group_id' => $group_id])
                                ->all();
        $student_tests = [];
        $students = UserGroup::getCurrentStudents(Group::getPreviousGroups($group_id));
        foreach($group_test as $test){
            foreach($students as $id => $fio){
                $student_test = StudentTest::find()
                                ->where(['group_test_id' => $test ->id, 'user_id' => $id])
                                ->orderBy(['id' => SORT_DESC])
                                ->limit(1)
                                ->one();
                array_push($student_tests, $student_test);
            }
        }
     
        if($group_test){
            $marks = [];
            $marks[5] = 0;
            $marks[4] = 0;
            $marks[3] = 0;
            $marks[2] = 0;
            foreach($student_tests as $test){
                if($test){
                    $mark = $test['mark'];
                    $marks[$mark]++;
                }
                array_push($marks, $test);
            }
            return $marks;
        }else{
            return false;
        }
        
    }

    public static function getMarksPercent($group_id)
    {
        $group_test = GroupTest::find()->where(['group_id' => $group_id])->all();
        if($group_test){
            $all_count = 0;
            $marks = static::getMarks($group_id);
            $marks_percent = [];
            foreach($group_test as $test){
                $all_count += $test['val_5'];
                $all_count += $test['val_4'];
                $all_count += $test['val_3'];
                $all_count += $test['fails'];
            }
            if($all_count != 0){
                $marks_percent[$marks[5]] = round($marks[5] * 100 / $all_count, 1);
                $marks_percent[$marks[4]] = round($marks[4] * 100 / $all_count, 1);
                $marks_percent[$marks[3]] = round($marks[3] * 100 / $all_count, 1);
                $marks_percent[$marks[2]] = round($marks[2] * 100 / $all_count, 1);
            }


            return $marks_percent;
        }else{
            return false;
        }
    }

    public static function getTestsCount($group_id)
    {
        return GroupTest::find()->where(['group_id' => $group_id])->count();
    }
   
}
