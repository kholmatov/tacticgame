<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\Sections;

/**
 * SectionsSearch represents the model behind the search form about `app\models\Sections`.
 */
class SectionsSearch extends Sections
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'min', 'max', 'active','position_id'], 'integer'],
            [['title', 'shorttext', 'title_es', 'shorttext_es','tariff','moretext', 'youtube','photo', 'slide','avatar', 'duration','duration_es'], 'safe'],
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
        $query = Sections::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'min' => $this->min,
            'max' => $this->max,
            'active' => $this->active,
        ]);

        $query->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'shorttext_es', $this->shorttext_es])
            ->andFilterWhere(['like', 'title_es', $this->title_es])
            ->andFilterWhere(['like', 'shorttext', $this->shorttext])
            ->andFilterWhere(['like', 'tariff', $this->tariff])
            ->andFilterWhere(['like', 'moretext', $this->moretext])
            ->andFilterWhere(['like', 'photo_id', $this->photo])
            ->andFilterWhere(['like', 'slide', $this->slide])
            ->andFilterWhere(['like', 'avatar', $this->avatar])
            ->andFilterWhere(['like', 'duration', $this->duration])
            ->andFilterWhere(['like', 'duration_es', $this->duration_es])
            ->andFilterWhere(['like', 'position_id', $this->position_id]);

        return $dataProvider;
    }
}
