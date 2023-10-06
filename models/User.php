<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "user".
 *
 * @property int $id
 * @property string $name
 * @property string $surname
 * @property string $patronymic
 * @property string $auth_key
 * @property string $login
 * @property string $password
 * @property string|null $entered
 * @property string|null $graduated
 * @property string $email
 *
 * @property AuthAssignment[] $authAssignments
 * @property Deny[] $denies
 * @property AuthItem[] $itemNames
 * @property StudentAnswer[] $studentAnswers
 * @property StudentTest[] $studentTests
 * @property UserGroup[] $userGroups
 */
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;
class User extends ActiveRecord implements IdentityInterface
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'surname', 'patronymic', 'email'], 'required'],
            [['entered', 'graduated'], 'safe'],
            [['name', 'surname', 'patronymic', 'auth_key', 'login', 'password', 'email', 'password_2'], 'string', 'max' => 255],
            [['login'], 'unique'],
            [['email'], 'unique', 'targetClass' => User::class],
            ['email', 'email']
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Имя',
            'surname' => 'Фамилия',
            'patronymic' => 'Отчество',
            'auth_key' => 'Auth Key',
            'login' => 'Login',
            'password' => 'Пароль',
            'entered' => 'Дата поступления',
            'graduated' => 'Дата выпуска',
            'email' => 'Email',
            'password_2' => 'Временный пароль'
        ];
    }

    /**
     * Gets query for [[AuthAssignments]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAuthAssignments()
    {
        return $this->hasMany(AuthAssignment::class, ['user_id' => 'id']);
    }

    /**
     * Gets query for [[Denies]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getDenies()
    {
        return $this->hasMany(Deny::class, ['user_id' => 'id']);
    }

    /**
     * Gets query for [[ItemNames]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getItemNames()
    {
        return $this->hasMany(AuthItem::class, ['name' => 'item_name'])->viaTable('auth_assignment', ['user_id' => 'id']);
    }

    /**
     * Gets query for [[StudentAnswers]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getStudentAnswers()
    {
        return $this->hasMany(StudentAnswer::class, ['user_id' => 'id']);
    }

    /**
     * Gets query for [[StudentTests]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getStudentTests()
    {
        return $this->hasMany(StudentTest::class, ['user_id' => 'id']);
    }

    /**
     * Gets query for [[UserGroups]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUserGroups()
    {
        return $this->hasMany(UserGroup::class, ['user_id' => 'id']);
    }
    public static function findIdentity($id)
    {
        return static::findOne($id);
    }

    /**
     * Finds an identity by the given token.
     *
     * @param string $token the token to be looked for
     * @return IdentityInterface|null the identity object that matches the given token.
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        return static::findOne(['access_token' => $token]);
    }

    /**
     * @return int|string current user ID
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string current user auth key
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * @param string $authKey
     * @return bool if auth key is valid for current user
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    public function validatePassword($password)
    {
        return Yii::$app->getSecurity()->validatePassword($password, $this -> password);
    }

    public static function findByUsername($login)
    {
        return static::findOne(['login' => $login]);
    }

    public function beforeSave($insert)
    {
        
        if (parent::beforeSave($insert)) {
            if ($this->isNewRecord) {
                $this->auth_key = \Yii::$app->security->generateRandomString();
                $this -> login = \Yii::$app->security->generateRandomString(8);
                $this->password_2 = Yii::$app->security->generateRandomString(8); 
                $this -> password = Yii::$app->getSecurity()->generatePasswordHash($this->password_2);
            }
            return true;
        }
        return false;
    }

    public static function getFIO($id)
    {
        $surname = static::findOne(['id' => $id]) -> surname;
        $name = static::findOne(['id' => $id]) -> name;
        $patronymic = static::findOne(['id' => $id]) -> patronymic;
        $fio = $surname . ' ' . $name . ' ' . $patronymic;
        return $fio;
    }

    public static function getTeachersList()
    {
        $teachers = User::find()
                        ->innerJoin(['auth_as'=>'auth_assignment'], 'auth_as.user_id = user.id')
                        ->where(['auth_as.item_name' => 'teacher'])
                        ->indexBy('id')
                        ->all();
        $teachers_with_fio = [];
        foreach($teachers as $id => $teacher){
            $teacherFIO = static::getFIO($id);
            $teachers_with_fio[$id] = $teacherFIO;
        }
        return $teachers_with_fio;
    }

   

    public function getUserGroup()
    {
        return UserGroup::getGroupTitleByUser($this->id);
    }

    public function getGroupId()
    {  
        return $this->userGroup->group_id;
    }

    public function getCurrentGroupById()
    {
        $all_groups = $this->userGroups;
        $current_group = Group::getCurrentGroup($all_groups[0]['group_id']);
        return $current_group;
        
    }

    public static function deletePassword($id)
    {
        $user = static::findOne($id);
        $user->password = '';
        $user->password_2 = '';
        return $user->save();
    }
}
