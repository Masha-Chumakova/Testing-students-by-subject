<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\user;
use Yii;
use yii\helpers\VarDumper;

/**
 * UserSearch represents the model behind the search form of `app\models\user`.
 */
class UserSearch extends user
{
    
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['name', 'surname', 'patronymic', 'auth_key'], 'safe'],
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
        

        $query = user::find()
                        ->innerJoin(['auth_as'=>'auth_assignment'], 'auth_as.user_id = user.id');
        if( Yii::$app->user->can('teacher') ) {
             $query->where(['auth_as.item_name' => 'student', 'user.graduated' => null]);
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
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'surname', $this->surname])
            ->andFilterWhere(['like', 'patronymic', $this->patronymic])
            ->andFilterWhere(['like', 'auth_key', $this->auth_key]);
        
       
         
        return $dataProvider;
    }
}
