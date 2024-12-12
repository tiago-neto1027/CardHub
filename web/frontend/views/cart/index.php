<?php
/** @var array $cartItems */
/** @var array $products */

use yii\helpers\Html;

if (empty($cartItems)) {
    echo "<p>Your cart is empty.</p>";
    return;
}

?>

<table class="table">
    <thead>
    <tr>
        <th>Item</th>
        <th>Quantity</th>
        <th>Price</th>
        <th>Total</th>
        <th>Actions</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($cartItems as $item): ?>
        <?php $product = $products[$item['product_id']]; ?>
        <tr>
            <td><?= Html::encode($product->name) ?></td>
            <td><?= $product['quantity'] ?></td>
            <td><?= Yii::$app->formatter->asCurrency($product->price) ?></td>
            <td><?= Yii::$app->formatter->asCurrency($product->price * $item['quantity']) ?></td>
            <td>
                <?= Html::a('Remove', ['cart/remove-from-cart', 'id' => $item['product_id']], [
                    'class' => 'btn btn-danger',
                    'data-method' => 'post',
                    'data-confirm' => 'Are you sure?',
                ]) ?>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>

<?= Html::a('Clear Cart', ['cart/clear-cart'], [
    'class' => 'btn btn-warning',
    'data-method' => 'post',
    'data-confirm' => 'Are you sure you want to clear the cart?',
]) ?>
