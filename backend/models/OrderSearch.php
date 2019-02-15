<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\Order;

/**
 * OrderSearch represents the model behind the search form about `backend\models\Order`.
 */
class OrderSearch extends Order
{
    public $section_title;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'sections_id', 'status', 'check'], 'integer'],
            [['payment_type','date', 'time', 'name', 'phone', 'email', 'comment', 'createdate', 'code','section'], 'safe'],
            [['amount'], 'number'],
        ];
    }

    /**
     * @inheritdoc
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
        $query = Order::find();

            //->joinWith(['sections_id' => function($query) { $query->from(['section' => 'sections']);}]);;

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort'=> ['defaultOrder' => ['createdate'=>SORT_DESC]]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }


        $query->andFilterWhere([
            'id' => $this->id,
            'sections_id' => $this->sections_id,
            'payment_type' => $this->payment_type,
            'date' => $this->date,
            'amount' => $this->amount,
            'status' => $this->status,
            'check' => $this->check,
            'createdate' => $this->createdate,
        ]);

        $query->andFilterWhere(['like', 'time', $this->time])
            ->andFilterWhere(['like', 'payment_type', $this->payment_type])
            ->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'phone', $this->phone])
            ->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['like', 'comment', $this->comment])
            ->andFilterWhere(['like', 'code', $this->code]);

        return $dataProvider;
    }
}
