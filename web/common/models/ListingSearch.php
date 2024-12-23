<?php

namespace common\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Listing;

/**
 * ListingSearch represents the model behind the search form of `common\models\Listing`.
 */
class ListingSearch extends Listing
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'seller_id', 'card_id'], 'integer'],
            [['price'], 'number'],
            [['condition', 'status', 'created_at', 'updated_at'], 'safe'],
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
        $query = Listing::find();

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

        if (isset($params['seller_id'])) {
            $query->andWhere(['seller_id' => $params['seller_id']]);
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'seller_id' => $this->seller_id,
            'card_id' => $this->card_id,
            'price' => $this->price,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'condition', $this->condition])
            ->andFilterWhere(['like', 'status', $this->status]);

        return $dataProvider;
    }
}
