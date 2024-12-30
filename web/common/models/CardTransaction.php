<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "card_transactions".
 *
 * @property int $id
 * @property int $seller_id
 * @property int $buyer_id
 * @property int $listing_id
 * @property string $status
 * @property string $date
 *
 * @property User $buyer
 * @property InvoiceLine $invoiceLines
 * @property Listing $listing
 * @property Reports $reports
 * @property Reviews $reviews
 * @property User $seller
 */
class CardTransaction extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'card_transactions';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['seller_id', 'buyer_id', 'listing_id'], 'required'],
            [['seller_id', 'buyer_id', 'listing_id'], 'integer'],
            [['status'], 'string'],
            [['date'], 'safe'],
            [['buyer_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['buyer_id' => 'id']],
            [['listing_id'], 'exist', 'skipOnError' => true, 'targetClass' => Listing::class, 'targetAttribute' => ['listing_id' => 'id']],
            [['seller_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['seller_id' => 'id']],
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
            'buyer_id' => 'Buyer ID',
            'listing_id' => 'Listing ID',
            'status' => 'Status',
            'date' => 'Date',
        ];
    }

    /**
     * Gets query for [[Buyer]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getBuyer()
    {
        return $this->hasOne(User::class, ['id' => 'buyer_id']);
    }

    /**
     * Gets query for [[InvoiceLines]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getInvoiceLines()
    {
        return $this->hasOne(InvoiceLines::class, ['card_transaction_id' => 'id']);
    }

    /**
     * Gets query for [[Listing]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getListing()
    {
        return $this->hasOne(Listing::class, ['id' => 'listing_id']);
    }

    /**
     * Gets query for [[Reports]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getReports()
    {
        return $this->hasOne(Reports::class, ['transaction_id' => 'id']);
    }

    /**
     * Gets query for [[Reviews]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getReviews()
    {
        return $this->hasOne(Reviews::class, ['transaction_id' => 'id']);
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
}
