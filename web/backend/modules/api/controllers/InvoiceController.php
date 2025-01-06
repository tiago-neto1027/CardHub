<?php

namespace backend\modules\api\controllers;

use backend\modules\api\controllers\BaseController;
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

}