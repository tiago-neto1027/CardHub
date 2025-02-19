<?php
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $payment common\models\Payment */
/* @var $transactions common\models\Transaction[] */

$this->title = 'Payment Successful';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="payment-success">
    <h1 class="text-light"><?= Html::encode($this->title) ?></h1>
    <p>Your payment has been successfully processed. Below are the details of your transaction:</p>

    <?= DetailView::widget([
        'model' => $payment,
        'attributes' => [
            'payment_method',
            'total:currency',
            'status',
            'date:datetime',
        ],
    ]) ?>

    <p>
        <a href="<?= Yii::$app->urlManager->createUrl(['site/index']) ?>" class="btn btn-primary text-black">Home</a>
    </p>
</div>
