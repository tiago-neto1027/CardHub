<?php

namespace frontend\controllers;

use app\models\PaymentForm;
use common\models\CardTransaction;
use common\models\Invoice;
use common\models\InvoiceLine;
use common\models\Listing;
use common\models\Payment;
use common\models\Product;
use common\models\ProductTransaction;
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
        $totalCost = Cart::getTotalCost();

        $model = new PaymentForm();
        if ($totalCost <= 0) {
            return $this->render('cart/index');
        }
        return $this->render('view', [
            'cartItems' => $cartItems,
            'model' => $model,
            'totalCost' => $totalCost,
        ]);
    }

    public function actionCheckout()
    {
        $model = new PaymentForm();

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $userId = Yii::$app->user->id;

            $cartKey = Cart::getCartKey();
            $cartItems = Cart::getItems($cartKey) ?: [];
            $totalCost = Cart::getTotalCost();

            if (empty($cartItems)) {
                Yii::$app->session->setFlash('error', 'Your cart is empty.');
                return $this->redirect(['cart/index']);
            }

            $transaction = Yii::$app->db->beginTransaction();
            try {

                $payment = new Payment();
                $payment->user_id = $userId;
                $payment->payment_method = $model->payment_method;
                $payment->total = $totalCost;
                $payment->status = 'pending';
                $payment->date = time();

                if (!$payment->save()) {
                    throw new \Exception('Failed to create payment.');
                }

                $invoice = new Invoice();
                $invoice->payment_id = $payment->id;
                $invoice->client_id = $userId;
                $invoice->date = time();
                if (!$invoice->save()) {
                    throw new \Exception('Failed to create invoice.');
                }

                foreach ($cartItems as $item) {
                    if ($item['type'] === 'product') {
                        $transactionModel = new ProductTransaction();
                        $transactionModel->buyer_id = $userId;
                        $transactionModel->product_id = $item['itemId'];
                        $transactionModel->date = time();
                        $transactionModel->status = 'pending';
                    }
                    if ($item['type'] === 'listing') {
                        $product = Listing::findOne($item['itemId']);
                        $transactionModel = new CardTransaction();
                        $transactionModel->seller_id = $product->seller_id;
                        $transactionModel->buyer_id = $userId;
                        $transactionModel->listing_id = $item['itemId'];
                        $transactionModel->date = time();
                        $transactionModel->status = 'pending';
                    }

                    if ($transactionModel) {
                        if (!$transactionModel->save()) {
                            throw new \Exception('Failed to create transaction for item ID: ' . $item['itemId']);
                        }

                        $invoiceLine = new InvoiceLine();
                        $invoiceLine->invoice_id = $invoice->id;
                        $invoiceLine->price = $item['price'] * $item['quantity'];
                        $invoiceLine->quantity = $item['quantity'];
                        $invoiceLine->product_name = $item['name'];
                        if ($item['type'] === 'listing') {
                            $invoiceLine->card_transaction_id = $transactionModel->id;
                        } elseif ($item['type'] === 'product') {
                            $invoiceLine->product_transaction_id = $transactionModel->id;
                        }

                        if (!$invoiceLine->save()) {
                            throw new \Exception('Failed to create invoice line for transaction ID: ' . $transactionModel->id);
                        }
                    } else {
                        throw new \Exception('Invalid item type or missing transaction model for item ID: ' . $item['itemId']);
                    }
                }

                Cart::clearCart();

                $transaction->commit();

                return $this->redirect(['success', 'id' => $payment->id]);
            } catch (\Exception $e) {
                $transaction->rollBack();
                Yii::$app->session->setFlash('error', $e->getMessage());
                return $this->redirect(['cart/index']);
            }
        }

        return $this->render('index', ['model' => $model]);
    }


    public function actionSuccess($id)
    {
        $payment = Payment::findOne($id);

        if (!$payment || $payment->user_id !== Yii::$app->user->id) {
            throw new \Exception('Payment not found or access denied.');
        }

        $transaction = Yii::$app->db->beginTransaction();
        try {
            $payment->status = 'complete';
            if (!$payment->save()) {
                throw new \Exception('Failed to update payment status.');
            }

            $invoice = Invoice::findOne(['payment_id' => $payment->id]);
            if (!$invoice) {
                throw new \Exception('Invoice not found for this payment.');
            }

            $invoiceLines = InvoiceLine::findAll(['invoice_id' => $invoice->id]);
            if (empty($invoiceLines)) {
                throw new \Exception('No invoice lines found.');
            }

            foreach ($invoiceLines as $invoiceLine) {
                if ($invoiceLine->card_transaction_id) {
                    $transactionModel = CardTransaction::findOne($invoiceLine->card_transaction_id);
                    if (!$transactionModel) {
                        throw new \Exception('Card transaction not found for invoice line ID: ' . $invoiceLine->id);
                    }

                    $transactionModel->status = 'completed';
                    if (!$transactionModel->save()) {
                        throw new \Exception('Failed to update CardTransaction status for transaction ID: ' . $transactionModel->id);
                    }

                    $listing = Listing::findOne($transactionModel->listing_id);
                    if ($listing) {
                        $listing->status = 'inactive';
                        if (!$listing->save()) {
                            throw new \Exception('Failed to update listing status to inactive for listing ID: ' . $listing->id);
                        }
                    } else {
                        throw new \Exception('Listing not found for CardTransaction ID: ' . $transactionModel->id);
                    }
                }

                if ($invoiceLine->product_transaction_id) {
                    $transactionModel = ProductTransaction::findOne($invoiceLine->product_transaction_id);
                    if (!$transactionModel) {
                        throw new \Exception('Product transaction not found for invoice line ID: ' . $invoiceLine->id);
                    }

                    $transactionModel->status = 'completed';
                    if (!$transactionModel->save()) {
                        throw new \Exception('Failed to update ProductTransaction status for transaction ID: ' . $transactionModel->id);
                    }

                    $product = Product::findOne($transactionModel->product_id);
                    if ($product) {
                        $product->stock -= 1;
                        if (!$product->save()) {
                            throw new \Exception('Failed to reduce product quantity for product ID: ' . $product->id);
                        }
                    } else {
                        throw new \Exception('Product not found for ProductTransaction ID: ' . $transactionModel->id);
                    }
                }
            }

            $transaction->commit();

            return $this->render('success', ['payment' => $payment]);

        } catch (\Exception $e) {
            $transaction->rollBack();
            Yii::$app->session->setFlash('error', $e->getMessage());
            return $this->redirect(['cart/index']);
        }
    }
}