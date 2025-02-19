<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "invoice_lines".
 *
 * @property int $id
 * @property int $invoice_id
 * @property float $price
 * @property string $product_name
 * @property int $quantity
 * @property int|null $card_transaction_id
 * @property int|null $product_transaction_id
 *
 * @property CardTransaction $cardTransaction
 * @property Invoice $invoice
 * @property ProductTransaction $productTransaction
 */
class InvoiceLine extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'invoice_lines';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['invoice_id', 'price', 'product_name', 'quantity'], 'required'],
            [['invoice_id', 'quantity', 'card_transaction_id', 'product_transaction_id'], 'integer'],
            [['price'], 'number'],
            [['product_name'], 'string'],
            [['card_transaction_id'], 'unique'],
            [['product_transaction_id'], 'unique'],
            [['card_transaction_id'], 'exist', 'skipOnError' => true, 'targetClass' => CardTransaction::class, 'targetAttribute' => ['card_transaction_id' => 'id']],
            [['invoice_id'], 'exist', 'skipOnError' => true, 'targetClass' => Invoice::class, 'targetAttribute' => ['invoice_id' => 'id']],
            [['product_transaction_id'], 'exist', 'skipOnError' => true, 'targetClass' => ProductTransaction::class, 'targetAttribute' => ['product_transaction_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'invoice_id' => 'Invoice ID',
            'price' => 'Price',
            'product_name' => 'Product Name',
            'quantity' => 'Quantity',
            'card_transaction_id' => 'Card Transaction ID',
            'product_transaction_id' => 'Product Transaction ID',
        ];
    }



    /**
     * Gets query for [[Invoice]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getInvoice()
    {
        return $this->hasOne(Invoice::class, ['id' => 'invoice_id']);
    }

    /**
     * Gets query for [[ProductTransaction]].
     *
     * @return \yii\db\ActiveQuery
     */

    public function getProductTransaction()
    {
        return $this->hasOne(ProductTransaction::class, ['id' => 'product_transaction_id']);
    }

    /**
     * Gets query for [[CardTransaction]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCardTransaction()
    {
        return $this->hasOne(CardTransaction::class, ['id' => 'card_transaction_id']);
    }
    public function getBuyer()
    {
        if ($this->card_transaction_id !== null){
            return $this->hasOne(User::class, ['id' => 'buyer_id'])->via('cardTransaction');
        } elseif ($this->product_transaction_id !== null) {
            return $this->hasOne(User::class, ['id' => 'buyer_id'])->via('productTransaction');
        }
        return null;
    }

    public function getSeller()
    {
        if ($this->card_transaction_id !== null){
            return $this->hasOne(User::class, ['id' => 'seller_id'])->via('cardTransaction');
        }
        return null;
    }

    public static function calculateMonthlyProfit($month, $year, $type) {
        $query = \common\models\InvoiceLine::find()
            ->alias('il')
            ->joinWith($type === 'card' ? 'cardTransaction ct' : 'productTransaction pt')
            ->andWhere(['IS NOT', $type === 'card' ? 'il.card_transaction_id' : 'il.product_transaction_id', null]);

        if ($type === 'card') {
            $query->andWhere([
                'MONTH(ct.date)' => $month,
                'YEAR(ct.date)' => $year,
            ]);
        } elseif ($type === 'product') {
            $query->andWhere([
                'MONTH(pt.date)' => $month,
                'YEAR(pt.date)' => $year,
            ]);
        }

        return $query->sum('il.price') ?? 0;
    }
}