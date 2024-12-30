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
     * Gets query for [[CardTransaction]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCardTransaction()
    {
        return $this->hasOne(CardTransactions::class, ['id' => 'card_transaction_id']);
    }

    /**
     * Gets query for [[Invoice]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getInvoice()
    {
        return $this->hasOne(Invoices::class, ['id' => 'invoice_id']);
    }

    /**
     * Gets query for [[ProductTransaction]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getProductTransaction()
    {
        return $this->hasOne(ProductTransactions::class, ['id' => 'product_transaction_id']);
    }
}
