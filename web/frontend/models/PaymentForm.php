<?php

namespace app\models;

use common\models\Payment;
use Yii;
use yii\base\Model;

class PaymentForm extends Model
{
    public $payment_method;

    /**
     * Define validation rules for the form.
     */
    public function rules()
    {
        return [
            [['payment_method'], 'required'], // The payment method is required
            [['payment_method'], 'in', 'range' => array_keys(Payment::getPaymentMethods())], // Ensure the value is valid
        ];
    }

}