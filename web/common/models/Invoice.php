<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "Invoices".
 *
 * @property int $id
 * @property int $client_id
 * @property int $payment_id
 * @property string $date
 */
class Invoice extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'invoices';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['client_id', 'payment_id'], 'required'],
            [['client_id', 'payment_id'], 'integer'],
            [['date'], 'safe'],
            [['payment_id'], 'unique'],
            [['client_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['client_id' => 'id']],
            [['payment_id'], 'exist', 'skipOnError' => true, 'targetClass' => Payment::class, 'targetAttribute' => ['payment_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'client_id' => 'Client ID',
            'payment_id' => 'Payment ID',
            'date' => 'Date',
        ];
    }

    public static function getInvoicesByUser($userId)
    {
        return self::find()->where(['client_id' => $userId])->all();
    }

    public function getPayment()
    {
        return $this->hasOne(Payment::class, ['id' => 'payment_id']);
    }

    public function getStatus()
    {
        return $this->payment ? $this->payment->status : null;
    }

    public function getTotal()
    {
        return $this->payment->total;
    }

    public function getInvoiceLines()
    {
        return $this->hasMany(InvoiceLine::class, ['invoice_id' => 'id']);
    }

}
