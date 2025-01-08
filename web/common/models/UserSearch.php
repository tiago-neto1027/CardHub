<?php

namespace common\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\User;

/**
 * UserSearch represents the model behind the search form of `common\models\User`.
 */
class UserSearch extends User
{
    public $user_type;
    public $listings;
    public $sold_listings;
    public $revenue;
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'status', 'created_at', 'updated_at','listings','sold_listings'], 'integer'],
            [['username', 'auth_key', 'password_hash', 'password_reset_token', 'email', 'verification_token', 'user_type', 'revenue'], 'safe'],
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
    public function search($params, $onlyDeleted = false, $onlySellers = false)
    {
        $query = User::find();

        $query->leftJoin('auth_assignment', 'auth_assignment.user_id = user.id');

        // add conditions that should always apply here
        if ($onlyDeleted) {
            $query->andWhere(['status' => 'deleted']);
        } else {
            $query->andWhere(['!=', 'status', 'deleted']);
        }

        if($onlySellers){
            $query->andWhere(['auth_assignment.item_name' => 'seller']);
        }

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'attributes' => [
                    'username',
                    'email',
                    'role' => [
                        'asc' => ['auth_assignment.item_name' => SORT_ASC],
                        'desc' => ['auth_assignment.item_name' => SORT_DESC],
                        'default' => SORT_ASC,
                    ],
                    'created_at',
                    'updated_at',
                    'listings' => [
                        'asc' => ['(SELECT COUNT(*) FROM listings WHERE listings.seller_id = user.id AND listings.status = "active")' => SORT_ASC],
                        'desc' => ['(SELECT COUNT(*) FROM listings WHERE listings.seller_id = user.id AND listings.status = "active")' => SORT_DESC],
                        'default' => SORT_DESC,
                    ],
                    'sold_listings' => [
                        'asc' => ['(SELECT COUNT(*) FROM listings WHERE listings.seller_id = user.id AND listings.status = "inactive")' => SORT_ASC],
                        'desc' => ['(SELECT COUNT(*) FROM listings WHERE listings.seller_id = user.id AND listings.status = "inactive")' => SORT_DESC],
                        'default' => SORT_DESC,
                    ],
                    'revenue' => [
                        'asc' => ['(SELECT SUM(price) FROM listings WHERE listings.seller_id = user.id AND listings.status = "inactive")' => SORT_ASC],
                        'desc' => ['(SELECT SUM(price) FROM listings WHERE listings.seller_id = user.id AND listings.status = "inactive")' => SORT_DESC],
                        'default' => SORT_DESC,
                    ],
                ],
            ],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            $query->where('0=1');
            return $dataProvider;
        }

        if ($this->listings !== null) {
            $query->andFilterHaving(['=', '(SELECT COUNT(*) FROM listings WHERE listings.seller_id = user.id)', $this->listings]);
        }

        if ($this->sold_listings !== null) {
            $query->andFilterHaving(['=', '(SELECT COUNT(*) FROM listings WHERE listings.seller_id = user.id AND listings.status = "inactive")', $this->sold_listings]);
        }

        if ($this->revenue !== null) {
            $query->andFilterHaving(['=', '(SELECT ROUND(SUM(price), 2) FROM listings WHERE listings.seller_id = user.id AND listings.status = "inactive")', $this->revenue]);
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'username', $this->username])
            ->andFilterWhere(['like', 'auth_key', $this->auth_key])
            ->andFilterWhere(['like', 'password_hash', $this->password_hash])
            ->andFilterWhere(['like', 'password_reset_token', $this->password_reset_token])
            ->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['like', 'verification_token', $this->verification_token])
            ->andFilterWhere(['auth_assignment.item_name' => $this->user_type]);

        return $dataProvider;
    }
}
