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
            $itemId = $item['product_id'];
            $product = $products[$itemId] ?? null;
            ?>
            <?php if ($product):?>

                <tr class="fs-5 text-center center ">
                    <td class="align-middle">
                        <?php
                        echo '
                         <a href="' . Url::to(['/catalog/view','id'=>$product->id]) . '" >
                             <img class="img-fluid w-100 rounded-3 " src="'.  $product->image_url .'" alt="">
                         </a>
                        '
                        ?>



                    </td>
                    <td class="col-3 align-self-center align-middle" title="Name"><?= Html::encode($product->name) ?></td>
                    <td class="align-middle"><?= $item['quantity'] ?></td>
                    <td class="align-middle"><?= Yii::$app->formatter->asCurrency($product->price) ?></td>
                    <td class="align-middle"><?= Yii::$app->formatter->asCurrency($product->price * $item['quantity']) ?></td>
                    <td class="align-middle">
                        <?= Html::a('Remove', ['cart/remove-from-cart', 'itemId' => $itemId,'type' => "product"], [
                            'class' => 'btn btn-danger btn-lg text-white',
                            'data-method' => 'post',
                        ]) ?>
                    </td>
                </tr>
            <?php endif; ?>
        <?php endforeach; ?>
        <tr class="fs-4">
            <th colspan="5" >Total</th>
            <th class="align-content-end">
                <?= Yii::$app->formatter->asCurrency($totalCost) ?>
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


