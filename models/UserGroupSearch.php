<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\UserGroup;
use Yii;
/**
 * UserGroupSearch represents the model behind the search form of `app\models\UserGroup`.
 */
class UserGroupSearch extends UserGroup
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'group_id', 'user_id'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        
        $query = UserGroup::find()
                            ->innerJoin(['auth_as'=>'auth_assignment'], 'auth_as.user_id = user_group.user_id');
        if( Yii::$app->user->can('teacher') ) {
            $query->where(['auth_as.item_name' => 'student']);
        } else {
            $query->where(['auth_as.item_name' => 'teacher']);
        }
        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'group_id' => $this->group_id,
            'user_id' => $this->user_id,
        ]);

        return $dataProvider;
    }
}
