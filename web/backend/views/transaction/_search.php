<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var common\models\InvoiceLineSearch $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="invoice-line-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'invoice_id') ?>

    <?= $form->field($model, 'price') ?>

    <?= $form->field($model, 'product_name') ?>

    <?= $form->field($model, 'quantity') ?>

    <?php // echo $form->field($model, 'card_transaction_id') ?>

    <?php // echo $form->field($model, 'product_transaction_id') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
