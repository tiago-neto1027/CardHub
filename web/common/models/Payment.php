<?php

namespace common\models;

use Yii;


/**
 * This is the model class for table "Payments".
 *
 * @property int $id
 * @property int $user_id
 * @property string $payment_method
 * @property string $status
 * @property float $total
 * @property string $date
 */
class Payment extends \yii\db\ActiveRecord
{
    const METHOD_PAYPAL = 'paypal';
    const METHOD_MBWAY = 'mbway';

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'payments';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'payment_method', 'total'], 'required'],
            [['user_id'], 'integer'],
            [['payment_method', 'status'], 'string'],
            [['total'], 'number'],
            [['date'], 'safe'],
            [['payment_method'], 'in', 'range' => [self::METHOD_PAYPAL, self::METHOD_MBWAY], 'message' => 'Invalid payment method.'],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'id']],
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
            'payment_method' => 'Payment Method',
            'status' => 'Status',
            'total' => 'Total',
            'date' => 'Date',
        ];
    }

    /**
     *
     * @return array
     */
    public static function getPaymentMethods()
    {
        return [
            self::METHOD_PAYPAL => 'paypal',
            self::METHOD_MBWAY => 'mbway',
        ];
    }

    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }
}
