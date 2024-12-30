<?php

use common\models\Payment;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

?>

<div class="container mt-5">
    <h1 class="text-center mb-4">Checkout</h1>

    <div class="row justify-content-center">
        <!-- Cart Items Section -->
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Your Cart</h4>
                    <ul class="list-group mb-4">
                        <?php foreach ($cartItems as $item): ?>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <strong><?= Html::encode($item['name']) ?></strong><br>
                                    Quantity: <?= Html::encode($item['quantity']) ?><br>
                                    Price: $<?= Html::encode($item['price']) ?>
                                </div>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Payment Form Section -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <?php $form = ActiveForm::begin(['action' => ['payment/checkout']]); ?>
                    <h4 class="card-title">Payment Information</h4>

                    <div class="mb-3">
                        <?= $form->field($model, 'payment_method')->dropdownList(
                            Payment::getPaymentMethods(),
                            ['prompt' => 'Select Payment Method', 'class' => 'form-select']
                        )->label('Payment Method'); ?>
                    </div>

                    <div class="text-center">
                        <?= Html::submitButton('Make Payment', [
                            'class' => 'btn btn-primary btn-lg',
                            'style' => 'width: 100%; padding: 12px; font-size: 18px;'
                        ]) ?>
                    </div>

                    <?php ActiveForm::end(); ?>
                </div>
            </div>
        </div>
    </div>
</div>
