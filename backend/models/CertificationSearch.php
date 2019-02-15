<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\Certification;

/**
 * CertificationSearch represents the model behind the search form about `backend\models\Certification`.
 */
class CertificationSearch extends Certification
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id','period'], 'integer'],
            [['title','title_es', 'text','text_es', 'photo','gamers'], 'safe'],
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
        $query = Certification::find();

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
        ]);

        $query->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'title_es', $this->title_es])
            ->andFilterWhere(['like', 'text', $this->text])
            ->andFilterWhere(['like', 'text_es', $this->text_es])
            ->andFilterWhere(['like', 'period', $this->period])
            ->andFilterWhere(['like', 'photo', $this->photo]);

        return $dataProvider;
    }
}
