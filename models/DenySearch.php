<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Deny;
use Yii;
use app\models\GroupTest;
/**
 * DenySearch represents the model behind the search form of `app\models\Deny`.
 */
class DenySearch extends Deny
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'true_false', 'group_test_id', 'user_id'], 'integer'],
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
        $group_test_id = Yii::$app->request->get('group_test_id');
        $query = Deny::find()->where(['group_test_id' => $group_test_id]);

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
            'true_false' => $this->true_false,
            'group_test_id' => $this->group_test_id,
            'user_id' => $this->user_id,
        ]);

        return $dataProvider;
    }
}
