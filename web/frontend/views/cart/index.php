<?php
/** @var array $cartItems */
/** @var array $products */

use yii\helpers\Html;
use yii\helpers\Url;

if (empty($cartItems)) {
    echo "<p>Your cart is empty.</p>";
    return;
}

?>

<div class="container col-10">
    <table class="table">
        <thead class="table-info">
        <tr class="fs-4 text-center">
            <th class="col-2">Image</th>
            <th class="col-3">Name</th>
            <th class="col-1">Quantity</th>
            <th class="col-1">Price</th>
            <th class="col-1">Total</th>
            <th class="col-2">Actions</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($cartItems as $item): ?>
            <?php
            if (isset($item['itemId'])) {
                $itemId = $item['itemId'];
                $product = $products[$itemId] ?? null;
            } else {
                continue;
            }
            ?>
            <?php if ($product):?>

                <tr class="fs-5 text-center center ">
                    <td class="align-middle">
                        <?php
                        echo '
                         <a href="' . Url::to(['/catalog/view','id'=>$item['itemId']]) . '" >
                             <img class="img-fluid w-100 rounded-3 " src="'.  $item['image'] .'" alt="">
                         </a>
                        '
                        ?>
                    </td>
                    <td class="col-3 align-self-center align-middle" title="Name"><?= Html::encode(($product->name)) ?></td>

                    <?php if($item['type']==='product'):?>
                    <td class="col-1 align-self-center align-middle">
                        <div class="d-flex align-items-center">
                            <button class="btn btn-sm btn-danger me-2" onclick="updateQuantity(<?= $item['itemId'] ?>, 'decrement')">-</button>
                            <span id="quantity-<?= $item['itemId'] ?>"><?= $item['quantity'] ?></span>
                            <button class="btn btn-sm btn-success ms-2" onclick="updateQuantity(<?= $item['itemId'] ?>, 'increment')">+</button>
                        </div>
                    </td>
                    <?php elseif($item['type']==='listing'):?>
                        <td class="col-1 align-self-center align-middle" title="Name"><?= $item['quantity'] ?></td>
                    <?php endif; ?>
                    <td class="align-middle" id="product-price"><?= Yii::$app->formatter->asCurrency($item['price'], 'EUR')?></td>
                    <td class="align-middle" id="product-total-<?= $item['itemId'] ?>"><?= Yii::$app->formatter->asCurrency($item['price'] * $item['quantity'], 'EUR')?></td>
                    <td class="align-middle">
                        <?= Html::a('Remove', ['cart/remove-from-cart', 'itemId' => $itemId,'type' => $item['type']], [
                            'class' => 'btn btn-danger btn-lg text-white',
                            'data-method' => 'post',
                        ]) ?>
                    </td>
                </tr>
            <?php endif; ?>
        <?php endforeach; ?>
        <tr class="fs-4">
            <th colspan="5" >Total</th>
            <th class="align-content-end" id="cart-total">
                <?= Yii::$app->formatter->asCurrency($totalCost, 'EUR') ?>
            </th>
        </tr>
        </tbody>
    </table>
</div>
<div class="d-flex col-10 offset-1 justify-content-between">
    <?= Html::a('Clear Cart', ['cart/clear-cart'], [
        'class' => 'btn btn-warning text-dark',
        'data-method' => 'post',
        'data-confirm' => 'Are you sure you want to clear the cart?',
    ]) ?>

    <?= Html::a('Pay', ['cart/payment'], [
        'class' => 'btn btn-success text-light col-3',
        'data-method' => 'post',
    ]) ?>
</div>

<script>
    function updateQuantity(itemId, action = null, quantity = null) {
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        // Construct the URL with query parameters
        const url = new URL('<?= Url::to(['cart/update-quantity']) ?>', window.location.origin);
        url.searchParams.append('itemId', itemId);
        if (action) {
            url.searchParams.append('action', action);
        }
        if (quantity !== null) {
            url.searchParams.append('quantity', quantity);
        }

        // Perform the fetch request
        fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-Token': csrfToken // Ensure csrfToken is defined
            },
            body: JSON.stringify({})  // Empty body, as data is sent via URL query params
        })
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    document.getElementById(`quantity-${itemId}`).innerText = data.newQuantity;
                    document.getElementById(`product-total-${itemId}`).innerText = data.newTotal;
                    document.getElementById('cart-total').innerText = data.newCartTotal;
                } else {
                    alert(data.error || 'Failed to update quantity.');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Unexpected error occurred.');
            });
    }

</script>
