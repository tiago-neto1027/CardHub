<?php

namespace app\controllers;

use app\models\PaymentForm;
use common\models\Payment;
use frontend\models\Cart;
use Yii;
use yii\db\Transaction;

class PaymentController extends \yii\web\Controller
{
    public function actionIndex()
    {
        $model = new PaymentForm();
        return $this->render('index', ['model' => $model]);
    }

    public function actionView()
    {
        $cartKey = Cart::getCartKey();
        $cartItems = Cart::getItems($cartKey) ?: [];

        $model = new PaymentForm();

        return $this->render('view', [
            'cartItems' => $cartItems,
            'model' => $model,
        ]);
    }

    public function actionCheckout()
    {
        $model = new PaymentForm();

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $userId = Yii::$app->user->id;

            // Retrieve cart items from the cache
            $cartKey = Cart::getCartKey();
            $cartItems = Cart::getItems($cartKey) ?: [];

            if (empty($cartItems)) {
                Yii::$app->session->setFlash('error', 'Your cart is empty.');
                return $this->redirect(['cart/index']);
            }

            $transaction = Yii::$app->db->beginTransaction();
            try {
                // Calculate total amount
                $totalAmount = array_reduce($cartItems, function ($sum, $item) {
                    return $sum + ($item->price * $item->quantity);
                }, 0);

                // Create a new payment
                $payment = new Payment();
                $payment->user_id = $userId;
                $payment->total_amount = $totalAmount;
                $payment->payment_method = $model->payment_method; // Use validated form data
                $payment->status = 'pending';
                $payment->created_at = time();
                $payment->updated_at = time();

                if (!$payment->save()) {
                    throw new \Exception('Failed to create payment.');
                }

                // Create transactions for each cart item
                foreach ($cartItems as $item) {
                    $transactionModel = new Transaction();
                    $transactionModel->payment_id = $payment->id;
                    $transactionModel->item_id = $item->item_id;
                    $transactionModel->item_type = $item->item_type;
                    $transactionModel->amount = $item->price * $item->quantity;
                    $transactionModel->status = 'pending';
                    $transactionModel->created_at = time();
                    $transactionModel->updated_at = time();

                    if (!$transactionModel->save()) {
                        throw new \Exception('Failed to create transaction for item ID: ' . $item->item_id);
                    }
                }

                // Clear the user's cart after transactions are created
                Cart::clearCart();

                $transaction->commit();

                return $this->redirect(['payment/success', 'id' => $payment->id]);
            } catch (\Exception $e) {
                $transaction->rollBack();
                Yii::$app->session->setFlash('error', $e->getMessage());
                return $this->redirect(['cart/index']);
            }
        }

        // If validation fails, return to the form with errors
        return $this->render('index', ['model' => $model]);
    }


    public function actionSuccess($id)
    {
        $payment = Payment::findOne($id);

        if (!$payment || $payment->user_id !== Yii::$app->user->id) {
            throw new \Exception('Payment not found or access denied.');
        }

        return $this->render('success', ['payment' => $payment]);
    }



}
