<?php

use common\models\Payment;
use yii\helpers\Html;
use yii\widgets\ActiveForm;


?>

<div class="container mt-5">
    <h1 class="text-center mb-4">Your Cart</h1>

    <div class="card mx-auto" style="max-width: 600px;">
        <div class="card-body">
            <!-- Cart Items -->
            <ul class="list-group mb-4">
                <?php foreach ($items as $item): ?>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <div>
                            <strong><?= Html::encode($item['name']) ?></strong><br>
                            Quantity: <?= Html::encode($item['quantity']) ?><br>
                            Price: $<?= Html::encode($item['price']) ?>
                        </div>
                    </li>
                <?php endforeach; ?>
            </ul>

            <?php $form = ActiveForm::begin(['action' =>
                ['payment/checkout', 'invoice_id' => $invoice_id]]); ?>
            <div class="d-flex justify-content-between list-group-item">
                <span>Total cost:</span>
                <span><?= Yii::$app->formatter->asCurrency($totalCost, 'EUR') ?></span>
            </div>

            <div class="mb-3 mt-4">
                <?= $form->field($model, 'payment_method')->dropdownList(
                    Payment::getPaymentMethods(),
                    ['prompt' => 'Select Payment Method', 'class' => 'form-select']
                )->label('Payment Method'); ?>
            </div>

            <?= Html::submitButton('Make Payment', [
                'class' => 'btn btn-primary btn-block',
                'style' => 'font-size: 16px; padding: 10px;'
            ]) ?>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
