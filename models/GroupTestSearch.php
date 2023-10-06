<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\GroupTest;
use Yii;

/**
 * GroupTestSearch represents the model behind the search form of `app\models\GroupTest`.
 */
class GroupTestSearch extends GroupTest
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'val_5', 'val_4', 'val_3', 'fails', 'group_id', 'test_id'], 'integer'],
            [['date'], 'safe'],
            [['avg_points'], 'number'],
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
        // var_dump($this->denies);die;
        if(Yii::$app->controller->module->id == 'student'){
            $query = GroupTest::find()
            ->innerJoin('deny', 'deny.group_test_id = group_test.id')
            ->where(['group_test.group_id' => Yii::$app->user->identity->CurrentGroupById, 'deny.user_id' => Yii::$app->user->identity->id, 'deny.true_false' => 1]);
        }else{
            $query = GroupTest::find();  
        }
        

        

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
            'date' => $this->date,
            'avg_points' => $this->avg_points,
            'val_5' => $this->val_5,
            'val_4' => $this->val_4,
            'val_3' => $this->val_3,
            'fails' => $this->fails,
            'group_id' => $this->group_id,
            'test_id' => $this->test_id,
        ]);

        return $dataProvider;
    }
}
