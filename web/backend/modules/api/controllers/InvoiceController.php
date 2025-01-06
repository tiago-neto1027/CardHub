<?php

namespace backend\modules\api\controllers;

use backend\modules\api\controllers\BaseController;
use common\models\CardTransaction;
use common\models\Listing;
use common\models\Product;
use common\models\ProductTransaction;
use common\models\User;
use Yii;
use yii\filters\auth\HttpBasicAuth;
use yii\web\ForbiddenHttpException;
use common\models\Invoice;
use common\models\Payment;
use common\models\InvoiceLine;
use yii\web\NotFoundHttpException;

class InvoiceController extends BaseController{

    public function behaviors()
    {
        $behaviors = parent::behaviors();

        $behaviors['authenticator'] = [
            'class' => HttpBasicAuth::className(),
            'auth' => [$this, 'authintercept'],
        ];

        return $behaviors;
    }
    public function authintercept($username, $password)
    {
        $user = User::findByUsername($username);
        if ($user && $user->validatePassword($password))
        {
            $this->user=$user;
            return $user;
        }
        throw new ForbiddenHttpException('Wrong credentials.');
    }

    public function actionIndex()
    {
        return Invoice::find()->where(['client_id' => $this->user->id])->all();
    }

    public function actionView($id)
    {
        $invoice = Invoice::find()->where(['id' => $id, 'client_id' => $this->user->id])->with('invoiceLines')->one();

        if (!$invoice) {
            throw new NotFoundHttpException('Invoice not found.');
        }

        return $invoice;
    }

    //Updates the payment status
    public function actionUpdateStatus($id)
    {
        $postData = Yii::$app->request->post();

        $invoice = Invoice::findOne(['id' => $id, 'client_id' => $this->user->id]);
        if (!$invoice) {
            throw new NotFoundHttpException('Invoice not found.');
        }

        $payment = $invoice->payment;
        if (!$payment) {
            throw new NotFoundHttpException('Payment not found.');
        }

        $newStatus = $postData['status'] ?? null;

        if (!$newStatus) {
            return [
                'success' => false,
                'message' => 'Payment status not received.',
            ];
        }

        $payment->status = $newStatus;

        if ($payment->save()) {
            if ($newStatus === 'completed') {
                foreach ($invoice->invoiceLines as $line) {
                    if ($line->product_transaction_id) {
                        $productTransaction = ProductTransaction::findOne($line->product_transaction_id);
                        if ($productTransaction) {
                            $product = Product::findOne($productTransaction->product_id);
                            if ($product) {
                                if ($product->stock < $line->quantity) {
                                    return [
                                        'success' => false,
                                        'message' => 'The product doesn\'t have enough stock.',
                                        'errors' => [
                                            'product_id' => $product->id,
                                            'available_stock' => $product->stock,
                                            'required_quantity' => $line->quantity
                                        ],
                                    ];
                                }

                                $product->stock -= $line->quantity;
                                if (!$product->save()) {
                                    return [
                                        'success' => false,
                                        'message' => 'Error while updating product stock.',
                                        'errors' => $product->errors,
                                    ];
                                }
                            }
                        }
                    }
                }

                return [
                    'success' => true,
                    'message' => 'Payment status updated and stock updated successfully.',
                ];
            }

            return [
                'success' => true,
                'message' => 'Payment status updated successfully.',
            ];
        }

        return [
            'success' => false,
            'message' => 'Error while updating payment status.',
            'errors' => $payment->errors,
        ];
    }

    public function actionCreate()
    {
        $postData = Yii::$app->request->post();

        $items = $postData['items'] ?? [];
        $paymentMethod = $postData['payment_method'] ?? null;

        $validPaymentMethods = ['PayPal', 'MbWay'];
        if (!in_array($paymentMethod, $validPaymentMethods)) {
            return [
                'success' => false,
                'message' => 'Invalid payment method.',
            ];
        }

        if (empty($items)) {
            return [
                'success' => false,
                'message' => 'No items were sent to the invoice.',
            ];
        }

        $transaction = Yii::$app->db->beginTransaction();
        try {
            //Creates payment
            $payment = new Payment([
                'user_id' => $this->user->id,
                'payment_method' => $paymentMethod,
                //might seem odd but it just calculates the sum of the price * quantity
                'total' => array_reduce($items, function ($sum, $item) {
                    $itemDetails = $this->getItemDetails($item);
                    return $sum + ($itemDetails['price'] * ($item['type'] === 'listing' ? 1 : $item['quantity']));
                }, 0),
                'status' => 'pending',
                'date' => date('Y-m-d H:i:s'),
            ]);
            if (!$payment->save()) {
                throw new \Exception('Error saving payment.');
            }

            //Creates invoice
            $invoice = new Invoice([
                'payment_id' => $payment->id,
                'client_id' => $this->user->id,
                'date' => date('Y-m-d H:i:s'),
            ]);
            if (!$invoice->save()) {
                throw new \Exception('Error saving invoice.');
            }

            //Creates invoice_lines
            foreach ($items as $item) {
                $transactionModel = null;

                if ($item['type'] === 'product') {
                    $transactionModel = new ProductTransaction([
                        'buyer_id' => $this->user->id,
                        'product_id' => $item['itemId'],
                        'date' => date('Y-m-d H:i:s'),
                        'status' => 'active',
                    ]);
                } elseif ($item['type'] === 'listing') {
                    $listing = Listing::findOne($item['itemId']);
                    if (!$listing) {
                        throw new \Exception('Listing not found for item: ' . json_encode($item));
                    }
                    $transactionModel = new CardTransaction([
                        'seller_id' => $listing->seller_id,
                        'buyer_id' => $this->user->id,
                        'listing_id' => $item['itemId'],
                        'date' => date('Y-m-d H:i:s'),
                        'status' => 'active',
                    ]);
                }

                if ($transactionModel && !$transactionModel->save()) {
                    throw new \Exception('Error saving transaction to item: ' . json_encode($item));
                }

                $invoiceLine = new InvoiceLine([
                    'invoice_id' => $invoice->id,
                    'price' => $this->getItemDetails($item)['price'] * ($item['type'] === 'listing' ? 1 : $item['quantity']),
                    'quantity' => $item['type'] === 'listing' ? 1 : $item['quantity'],
                    'product_name' => $this->getItemDetails($item)['name'],
                    'product_transaction_id' => $transactionModel instanceof ProductTransaction ? $transactionModel->id : null,
                    'card_transaction_id' => $transactionModel instanceof CardTransaction ? $transactionModel->id : null,
                ]);

                if (!$invoiceLine->save()) {
                    throw new \Exception('Error saving invoice line to item: ' . json_encode($item));
                }
            }

            $transaction->commit();

            return [
                'success' => true,
                'message' => 'Invoice created successfully.',
                'data' => [
                    'invoice_id' => $invoice->id,
                    'payment_id' => $payment->id,
                    'total' => $payment->total,
                    'items' => $items,
                ],
            ];
        } catch (\Exception $e) {
            $transaction->rollBack();

            return [
                'success' => false,
                'message' => 'Error creating invoice: ' . $e->getMessage(),
            ];
        }
    }

    private function getItemDetails($item)
    {
        if ($item['type'] === 'product') {
            $product = Product::findOne($item['itemId']);
            if (!$product) {
                throw new \Exception('Product not found for itemId: ' . $item['itemId']);
            }
            return [
                'name' => $product->name,
                'price' => $product->price,
            ];
        } elseif ($item['type'] === 'listing') {
            $listing = Listing::findOne($item['itemId']);
            if (!$listing) {
                throw new \Exception('Listing not found for itemId: ' . $item['itemId']);
            }
            return [
                'name' => $listing->card->name,
                'price' => $listing->price,
            ];
        }

        throw new \Exception('Invalid item type: ' . $item['type']);
    }
}