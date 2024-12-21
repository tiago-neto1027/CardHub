<?php

namespace common\models;

use frontend\controllers\FavoritesController;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Favorites;

class FavoritesSearch extends Favorites
{
    public function rules()
    {
        return [
            [['name'], 'safe'],  // Define the validation rules for filtering
        ];
    }

    /**
     * @inheritdoc
     */
    public function search($params)
    {
        $query = Favorites::find()
            ->joinWith('card')  // Join with the 'card' relation to access card properties
            ->where(['favorites.user_id' => \Yii::$app->user->id]);  // Filter by user_id

        $this->load($params);

        if (!$this->validate()) {
            return new ActiveDataProvider([
                'query' => $query,
            ]);
        }

        return new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 10,
            ],
        ]);
    }

    public function getCard()
    {
        return $this->hasOne(Card::class, ['id' => 'card_id']);
    }


}