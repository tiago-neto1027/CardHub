<?php

namespace common\tests\unit\models;

use common\models\Payment;
use common\models\User;
use Codeception\Test\Unit;

class PaymentTest extends Unit
{
    private $user;
    private $payment;

    protected function _before()
    {
        // Create a user for the payment
        $this->user = new User();
        $this->user->username = "testuser";
        $this->user->email = "testuser@example.com";
        $this->user->password = "testpassword";
        $this->user->save();

        // Create a payment for the user
        $this->payment = new Payment();
        $this->payment->user_id = $this->user->id;
        $this->payment->payment_method = Payment::METHOD_PAYPAL;
        $this->payment->status = 'completed';
        $this->payment->total = 100.00;
        $this->payment->date = date("Y-m-d");
        $this->payment->save();
    }

    public function testPaymentValidation()
    {
        // Test invalid payment without required fields
        $payment = new Payment();
        $this->assertFalse($payment->validate(), 'Payment should not be valid without user_id, payment_method, and total.');

        // Test valid payment
        $payment->user_id = $this->user->id;
        $payment->payment_method = Payment::METHOD_PAYPAL;
        $payment->status = 'completed';
        $payment->total = 100.00;
        $payment->date = date("Y-m-d");
        $this->assertTrue($payment->validate(), 'Payment should be valid when all required fields are provided.');
    }

    public function testGetPaymentMethods()
    {
        // Test the available payment methods
        $paymentMethods = Payment::getPaymentMethods();
        $this->assertArrayHasKey(Payment::METHOD_PAYPAL, $paymentMethods, 'Payment methods should contain PayPal.');
        $this->assertArrayHasKey(Payment::METHOD_MBWAY, $paymentMethods, 'Payment methods should contain MB WAY.');
    }

    public function testUserRelation()
    {
        // Test the relation with the user
        $payment = Payment::findOne($this->payment->id);
        $user = $payment->user;
        $this->assertNotNull($user, 'Payment should be related to a user.');
        $this->assertEquals($this->user->id, $user->id, 'The user related to the payment should match the user_id.');
    }

    public function testInvalidPaymentMethod()
    {
        // Test an invalid payment method
        $payment = new Payment();
        $payment->user_id = $this->user->id;
        $payment->payment_method = 'invalid_method'; // Invalid method
        $payment->status = 'completed';
        $payment->total = 100.00;
        $payment->date = date("Y-m-d");
        $this->assertFalse($payment->validate(), 'Payment should not be valid with an invalid payment method.');
    }
}
