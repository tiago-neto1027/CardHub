<?php

namespace frontend\controllers;

use common\models\CardTransaction;
use common\models\Invoice;
use common\models\InvoiceLine;
use common\models\Listing;
use common\models\Payment;
use common\models\Product;
use common\models\ProductTransaction;
use Yii;
use yii\web\Controller;

class InvoiceController extends Controller
{
    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
                'access' => [
                    'class' => \yii\filters\AccessControl::class,
                    'only' => ['index','view'],
                    'rules' => [
                        [
                            'allow' => true,
                            'actions' => ['index','view'],
                            'roles' => ['seller','buyer'],
                        ]
                    ],
                ],
            ]
        );
    }
    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionView($id)
    {
        $invoice = Invoice::findOne($id);

        if (!$invoice || $invoice->client_id !== Yii::$app->user->id) {
            throw new \yii\web\NotFoundHttpException('Invoice not found or access denied.');
        }

        $invoiceLines = InvoiceLine::find()
            ->where(['invoice_id' => $invoice->id])
            ->all();

        $payment = $invoice->getPayment()->one();

        foreach ($invoiceLines as $invoiceLine) {
            if ($invoiceLine->product_transaction_id) {
                $productTransaction = ProductTransaction::findOne($invoiceLine->product_transaction_id);
                if ($productTransaction) {
                    $item = [
                        'itemId' => $productTransaction->product_id,
                        'name' => $productTransaction->product->name,
                        'quantity' => $invoiceLine->quantity,
                        'price' => $productTransaction->product->price,
                        'image' => $productTransaction->product->image_url,
                        'type' => 'Product'
                    ];
                    $items[] = $item;
                }
            }
            elseif ($invoiceLine->card_transaction_id) {
                $cardTransaction = CardTransaction::findOne($invoiceLine->card_transaction_id);
                if ($cardTransaction) {
                    $item = [
                        'itemId' => $cardTransaction->listing_id,
                        'name' => $cardTransaction->listing->card->name,
                        'quantity' => 1,
                        'price' => $cardTransaction->listing->price,
                        'image' => $cardTransaction->listing->card->image_url,
                        'type' => 'Listing'
                    ];
                    $items[] = $item;
                }
            }
        }
        return $this->render('view', [
            'invoice' => $invoice,
            'invoiceLines' => $invoiceLines,
            'items' => $items,
            'payment' => $payment,
        ]);
    }

    public function actionCancel($id)
    {
        $invoice = Invoice::findOne($id);

        if (!$invoice) {
            Yii::$app->session->setFlash('error', 'Invoice not found.');
            return $this->redirect(['detail/details' , 'id' => $id]);
        }

        if ($invoice->client_id !== Yii::$app->user->id) {
            Yii::$app->session->setFlash('error', 'You are not authorized to cancel this order.');
            return $this->redirect(['detail/details' , 'id' => $id]);
        }

        $payment = Payment::findOne($invoice->payment_id);

        if ($payment && $payment->status === 'Pending') {
            $transaction = Yii::$app->db->beginTransaction();
            try {
                $payment->status = 'Canceled';
                if (!$payment->save()) {
                    throw new \Exception('Failed to cancel the payment.');
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

                        $transactionModel->status = 'inactive';
                        if (!$transactionModel->save()) {
                            throw new \Exception('Failed to update CardTransaction status for transaction ID: ' . $transactionModel->id);
                        }

                        $listing = Listing::findOne($transactionModel->listing_id);
                        if ($listing) {
                            $listing->status = 'active';
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

                        $transactionModel->status = 'inactive';
                        if (!$transactionModel->save()) {
                            throw new \Exception('Failed to update ProductTransaction status for transaction ID: ' . $transactionModel->id);
                        }

                        $product = Product::findOne($transactionModel->product_id);
                        if ($product) {
                            $product->stock += $invoiceLine->quantity;
                            if (!$product->save()) {
                                throw new \Exception('Failed to reduce product quantity for product ID: ' . $product->id);
                            }
                        } else {
                            throw new \Exception('Product not found for ProductTransaction ID: ' . $transactionModel->id);
                        }
                    }
                }

                $transaction->commit();

                Yii::$app->session->setFlash('success', 'Your order has been canceled successfully.');
                return $this->redirect(['detail/details' , 'id' => $id]);

            } catch (\Exception $e) {
                $transaction->rollBack();
                Yii::$app->session->setFlash('error', 'Failed to cancel the order: ' . $e->getMessage());
                return $this->redirect(['detail/details' , 'id' => $id]);
            }
        } else {
            Yii::$app->session->setFlash('error', 'This order cannot be canceled because it is not pending.');
            return $this->redirect([['detail/details' , 'id' => $id]]);
        }
    }



}
