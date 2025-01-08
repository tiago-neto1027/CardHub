<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "listings".
 *
 * @property int $id
 * @property int $seller_id
 * @property int $card_id
 * @property float $price
 * @property string $condition
 * @property string $status
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Card $card
 * @property CardTransaction[] $cardTransactions
 * @property User $seller
 */
class Listing extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'listings';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['seller_id', 'card_id', 'price', 'condition'], 'required'],
            [['seller_id', 'card_id'], 'integer'],
            [['price'], 'number'],
            [['condition', 'status'], 'string'],
            [['card_id'], 'exist', 'skipOnError' => true, 'targetClass' => Card::class, 'targetAttribute' => ['card_id' => 'id']],
            [['seller_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['seller_id' => 'id']],
        ];
    }

    public function behaviors()
    {
        return [
            TimestampBehavior::class,
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'seller_id' => 'Seller ID',
            'card_id' => 'Card Name',
            'price' => 'Price',
            'condition' => 'Condition',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
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
     * Gets query for [[CardTransactions]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCardTransaction()
    {
        return $this->hasOne(CardTransaction::class, ['listing_id' => 'id']);
    }

    /**
     * Gets query for [[Seller]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSeller()
    {
        return $this->hasOne(User::class, ['id' => 'seller_id']);
    }

    public static function getSoldListingsCount()
    {
        return self::find()->where(['status' => 'inactive'])->count();
    }
}
