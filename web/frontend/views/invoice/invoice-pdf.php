<h1>Invoice #<?= $invoice->id ?></h1>
<p><strong>Date:</strong> <?= Yii::$app->formatter->asDate($invoice->date) ?></p>

<table style="width: 100%; border-collapse: collapse; margin: 20px 0;">
    <thead>
    <tr style="background-color: #f4f4f4;">
        <th style="border: 1px solid #ddd; padding: 10px; text-align: center;">Item</th>
        <th style="border: 1px solid #ddd; padding: 10px; text-align: center;">Quantity</th>
        <th style="border: 1px solid #ddd; padding: 10px; text-align: center;">Price</th>
        <th style="border: 1px solid #ddd; padding: 10px; text-align: center;">Total</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($items as $item): ?>
        <tr>
            <td style="border: 1px solid #ddd; padding: 10px; text-align: center;"><?= $item['name'] ?></td>
            <td style="border: 1px solid #ddd; padding: 10px; text-align: center;"><?= $item['quantity'] ?></td>
            <td style="border: 1px solid #ddd; padding: 10px; text-align: center;">
                <?= Yii::$app->formatter->asCurrency($item['price'], 'EUR') ?>
            </td>
            <td style="border: 1px solid #ddd; padding: 10px; text-align: center;">
                <?= Yii::$app->formatter->asCurrency($item['quantity'] * $item['price'], 'EUR') ?>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>

<p><strong>Total: </strong><?= Yii::$app->formatter->asCurrency($payment->total) ?></p>
<p><strong>Payment Method: </strong><?= $payment->payment_method ?></p>

