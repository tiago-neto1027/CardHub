<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "favorites".
 *
 * @property int $id
 * @property int $user_id
 * @property int $card_id
 *
 * @property Card $card
 * @property User $user
 */
class Favorite extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'favorites';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'card_id'], 'required'],
            [['user_id', 'card_id'], 'integer'],
            [['user_id', 'card_id'], 'unique', 'targetAttribute' => ['user_id', 'card_id'], 'message' => 'You can only favorite the same card once.'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'card_id' => 'Card ID',
        ];
    }

    /**
     * Gets query for [[Card]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCard()
    {
        return $this->hasOne(Card::class, ['id' => 'card_id']);
    }

    /**
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }
}
