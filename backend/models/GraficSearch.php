<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\Grafic;

/**
 * GraficSearch represents the model behind the search form about `app\models\Grafic`.
 */
class GraficSearch extends Grafic
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'section_item_id','active'], 'integer'],
            [['date', 'timearray'], 'safe'],
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
        $query = Grafic::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'section_item_id' => $this->section_item_id,
            'date' => $this->date,
            'active' => $this->active
        ]);

        $query->andFilterWhere(['like', 'timearray', $this->timearray]);

        return $dataProvider;
    }
}
