<?php
/** @var yii\web\View $this */
/** @var \common\models\Invoice $invoice */
/** @var \common\models\InvoiceLine[] $invoiceLines */

use yii\bootstrap5\Html;

$this->title = "Invoice #{$invoice->id}";
?>

<div class="mt-5">
    <h2 class="text-center mb-4">Invoice Details</h2>
    <div class="rounded bg-light p-4">
        <h3 class="mt-4">Purchased Items</h3>
        <?php if (!empty($invoiceLines)): ?>
            <table class="table table-bordered">
                <thead>
                <tr class="text-center">
                    <th class="col-2">Image</th>
                    <th class="col-2">Item</th>
                    <th class="col-2">Quantity</th>
                    <th class="col-2">Price</th>
                    <th class="col-2">Total</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($items as $item): ?>
                    <?php if (isset($item)): ?>
                        <tr class="fs-5 text-center center ">
                            <td class="align-middle">
                                <img class="img-fluid w-100 rounded-3" src="<?= htmlspecialchars($item['image']) ?>" alt="">
                            </td>

                            <td class="col-3 align-self-center align-middle" title="Name"><?= Html::encode(($item['name'])) ?></td>
                            <td class="col-1 align-middle">
                                <div class="d-flex align-items-center justify-content-center">
                                    <span id="quantity-<?= $item['itemId'] ?>"><?= $item['quantity'] ?></span>
                                </div>
                            </td>
                            <td class="align-middle" id="product-price"><?= Yii::$app->formatter->asCurrency($item['price'], 'EUR') ?></td>
                            <td class="align-middle" id="product-total-<?= $item['itemId'] ?>"><?= Yii::$app->formatter->asCurrency($item['price'] * $item['quantity'], 'EUR') ?></td>
                        </tr>
                    <?php endif; ?>
                <?php endforeach; ?>

                <tr class="fs-4">
                    <th colspan="4" class="text-end">Total</th>
                    <td class="align-middle text-center" id="cart-total"><?= Yii::$app->formatter->asCurrency($payment->total, 'EUR') ?></td>
                </tr>
                </tbody>
            </table>
        <h3 class="mt-4 mt-5">Payment status</h3>
            <table class="table table-bordered col-6">
                <thead>
                    <tr>
                        <th class="col-2">Payment Status</th>
                        <th class="col-2">Payment Method</th>
                        <th class="col-2">Date</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="col-3" title="Satuts"><?= Html::encode(($payment->status)) ?></td>
                        <?php if($payment->status == 'Completed'): ?>
                        <td class="col-3" title="Method"><?= Html::encode(($payment->payment_method)) ?></td>
                        <td class="col-3" title="Date"><?= Yii::$app->formatter->asDate($payment->date, 'php:Y-m-d') ?></td>
                        <?php else:?>
                        <td class="col-3" title="Method">Waiting payment</td>
                        <td class="col-3" title="Date"></td
                        <?php endif?>
                    </tr>
                </tbody>
            </table>
        <?php else: ?>
            <p>No items found for this invoice.</p>
        <?php endif; ?>
        <div class="d-flex justify-content-between">
            <?= Html::a('Back to Orders', ['/detail/details', 'id' => Yii::$app->user->id], [
                'class' => 'btn btn-primary mt-4'
            ]); ?>

            <?php if ($invoice->status === 'Pending'): ?>
                <div class="text-end">
                    <?= Html::a('Pay Now', ['/payment/view', 'source'=>'detail','id' => $invoice->id], [
                        'class' => 'btn btn-success rounded mt-4 text-black',
                        'role' => 'button'
                    ]); ?>
                </div>
            <?php endif; ?>
        </div>

    </div>
</div>
