<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\UserAdmin;

/**
 * UserAdminSearch represents the model behind the search form about `backend\models\UserAdmin`.
 */
class UserAdminSearch extends UserAdmin
{
    public $searchstring;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'status', 'role', 'created_at', 'updated_at'], 'integer'],
            [['username', 'auth_key', 'password_hash', 'password_reset_token', 'email','searchstring'], 'safe'],
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
        $query = UserAdmin::find();

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
            'status' => $this->status,
            'role' => $this->role,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

//        $query->andFilterWhere(['like', 'username', $this->username])
//            ->andFilterWhere(['like', 'auth_key', $this->auth_key])
//            ->andFilterWhere(['like', 'password_hash', $this->password_hash])
//            ->andFilterWhere(['like', 'password_reset_token', $this->password_reset_token])
//            ->andFilterWhere(['like', 'email', $this->email]);

        $query->orFilterWhere(['like', 'username', $this->searchstring])
            ->orFilterWhere(['like', 'email', $this->searchstring]);

        return $dataProvider;
    }
}
