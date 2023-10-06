<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "deny".
 *
 * @property int $id
 * @property int $true_false
 * @property int $group_test_id
 * @property int $user_id
 *
 * @property GroupTest $groupTest
 * @property User $user
 */
class Deny extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'deny';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['true_false', 'group_test_id', 'user_id'], 'required'],
            [['true_false', 'group_test_id', 'user_id'], 'integer'],
            [['group_test_id'], 'exist', 'skipOnError' => true, 'targetClass' => GroupTest::class, 'targetAttribute' => ['group_test_id' => 'id']],
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
            'true_false' => 'True False',
            'group_test_id' => 'Group Test ID',
            'user_id' => 'User ID',
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
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }

    public static function denyTest($id)
    {
        $deny = Deny::findOne($id);
        $deny -> true_false = 0;

        return $deny->save();
    }

    public static function accessTest($id)
    {
        $access = Deny::findOne($id);
        $access -> true_false = 1;
        return $access->save();
    }

    public static function denyForAll($group_test_id)
    {
        $all_denies = static::find()->where(['group_test_id' =>  $group_test_id])->asArray()->all();
        
        foreach( $all_denies as $deny){
            $deny_item = Deny::findOne([$deny['id']]);
            $deny_item->true_false = 0;
            $deny_item->save();
        }
        return true;
    }

    public static function accessForAll($group_test_id)
    {
        $all_denies = static::find()->where(['group_test_id' =>  $group_test_id])->asArray()->all();
        
        foreach( $all_denies as $deny){
            $deny_item = Deny::findOne([$deny['id']]);
            $deny_item->true_false = 1;
            $deny_item->save();
        }
        return true;
    }

    public static function createDeny($group_test_id, $group_students)
    {
        foreach($group_students as $student_id => $student){
            $deny = new static;
            $deny->true_false = 0;
            $deny -> group_test_id = $group_test_id;
            $deny -> user_id = $student_id;
            $res = $deny -> save();
            if( !$res){
                return $res;
            }
        }
        return true;
    }

    
}
