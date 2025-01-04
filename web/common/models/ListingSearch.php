<?php

namespace common\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Listing;
use common\models\Game;

/**
 * ListingSearch represents the model behind the search form of `common\models\Listing`.
 */
class ListingSearch extends Listing
{
    public $game;
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'seller_id', 'card_id', 'game'], 'integer'],
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

        $this->load($params);

        if (isset($params['seller_id'])) {
            $query->andWhere(['seller_id' => $params['seller_id']]);
        }

        if (isset($params['status'])) {
            $query->andWhere(['status' => $params['status']]);
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'seller_id' => $this->seller_id,
            'card_id' => $this->card_id,
            'price' => $this->price,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'condition', $this->condition]);

        if (!$this->validate()) {
            return new ActiveDataProvider([
                'query' => $query,
                'pagination' => [
                    'pageSize' => 20,
                ],
            ]);
        }

        return new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 20,
            ],
        ]);
    }

}
