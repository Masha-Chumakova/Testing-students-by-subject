<?php

namespace app\models;

use Symfony\Component\Console\Output\NullOutput;
use Yii;

/**
 * This is the model class for table "user_group".
 *
 * @property int $id
 * @property int $group_id
 * @property int $user_id
 *
 * @property Group $group
 * @property User $user
 */
class UserGroup extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user_group';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['group_id', 'user_id'], 'required'],
            [['group_id', 'user_id'], 'integer'],
            [['group_id'], 'exist', 'skipOnError' => true, 'targetClass' => Group::class, 'targetAttribute' => ['group_id' => 'id']],
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
            'group_id' => 'Номер группы',
            'user_id' => 'Преподаватель',
        ];
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
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }

    public function getGroupTitle()
    {
        return Group::getGroupTitle($this->group_id);
    }

    public function getTeacherOfGroup()
    {
        return User::getFIO($this->user_id);
    }

    public static function getGroupTitleByUser($user_id)
    {
        $group_id = static::findOne(['user_id' => $user_id]) -> group_id;
        return Group::getGroupTitle($group_id);
    }

    public static function getUsersOfGroup($group_id, $current_group_id)
    {
        $group_first_year = Group::findOne($current_group_id) -> year + 0;
        $group_second_year = Group::findOne($current_group_id) -> year + 1;
        $group_months = [];
        for($i = 9; $i <= 12; $i++){
            $group_months[$i] = $group_first_year;
        }
        for($i = 1; $i <= 6; $i++){
            $group_months[$i] = $group_second_year;
        }
        $user_ids = static::find()
                        ->innerJoin(['auth_as'=>'auth_assignment'], 'auth_as.user_id = user_group.user_id')
                        ->select(['user_group.user_id'])
                        ->where(['group_id' => $group_id,'auth_as.item_name' => 'student'])
                        ->asArray()
                        ->all();
        $ids = [];
        foreach($user_ids as $user_id){
            $user = User::findOne(['id' => $user_id['user_id']]);
            $fio = User::getFIO($user->id);
            $user_graduated_date = [];
            if($user -> graduated === null){
                
                $ids[$user->id] = $fio;
            }
            if($user->graduated){
                $date = explode('-', $user -> graduated);
                if(intdiv($date[1], 10) == 0){
                    $user_graduated_date[$date[1]%10] = $date[0];
                }else{
                    $user_graduated_date[$date[1]] = $date[0];
                }
            }
            
            if(Group::findOne($current_group_id) -> year.'-09-01' < $user->graduated){
                $ids[$user->id] = $fio;
            }
            foreach($group_months as $month => $year){
                $key = array_key_first($user_graduated_date);
                if($key == $month && $user_graduated_date[$key] == $year){
                    $ids[$user->id] = $fio;
                }
            }    
        }
        return $ids;
        
    }

    public static function getCurrentStudents($group_id)
    {
        $user_ids = static::find()
                        ->innerJoin(['auth_as'=>'auth_assignment'], 'auth_as.user_id = user_group.user_id')
                        ->select(['user_group.user_id'])
                        ->where(['group_id' => $group_id,'auth_as.item_name' => 'student'])
                        ->asArray()
                        ->all();
        $ids = [];
        foreach($user_ids as $user_id)
        {
            $user = User::findOne(['id' => $user_id['user_id']]);
            $fio = User::getFIO($user->id);
            if($user -> graduated > date('Y-m-d') || $user -> graduated === null){
                $ids[$user->id] = $fio;
            }
        }
        return $ids;
    }

    public static function getTeachersGroups($teacher_id)
    {
        return static::find()->where(['user_id' => $teacher_id])->asArray()->all();
    }
    
}
