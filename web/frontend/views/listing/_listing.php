<?php

use common\models\Listing;
use yii\helpers\Html;

?>
<div class="product-item bg-light mb-4">
    <div class="product-img position-relative overflow-hidden">
        <?= Html::img($model->card->image_url, [
            'alt' => html::encode($model->card->name),
            'class' => 'img-fluid w-100 p-1',
        ]); ?>
        <div class="product-action">
            <?php //Shopping Cart // Trash
            if ($model->seller_id != Yii::$app->user->identity->id) {
                echo Html::a('<i class="fa fa-shopping-cart"></i>',
                    ['/cart/add-to-cart', 'itemId' => $model->id, 'type' => $model instanceof Listing ? 'listing' : 'product'],
                    ['class' => 'btn btn-outline-dark btn-square btn-bg-dark']);
            } else {
                echo Html::a('<i class="fa fa-trash"></i>',
                    ['/listing/delete', 'id' => $model->id],
                    ['class' => 'btn btn-outline-dark btn-square btn-bg-dark', 'data-method' => 'post',
                        'data-confirm' => 'Are you sure you want to remove this item from your listings?']);
            } ?>

            <?php // Favorite Button
            if (!$model->card->isFavorited()) {
                echo Html::a('<i class="far fa-heart"></i>',
                    ['/favorite/create', 'id' => $model->card_id],
                    ['class' => 'btn btn-outline-dark btn-square']);
            } else {
                echo Html::a('<i class="fas fa-heart-broken"></i>',
                    ['/favorite/remove', 'id' => $model->card_id],
                    ['class' => 'btn btn-outline-dark btn-square',
                        'disabled' => true,
                        'title' => 'This item is already in your favorites',]);
            } ?>

            <?= Html::a('<i class="fa fa-search"></i>',
                ['/listing/view', 'id' => $model->id],
                ['class' => 'btn btn-outline-dark btn-square']); ?>
        </div>
    </div>
    <div class="text-center py-4">
        <?= Html::a(html::encode($model->card->name),
            ['/listing/view', 'id' => $model->id],
            ['class' => 'h6 text-decoration-none text-truncate d-block px-3', 'title' => html::encode($model->card->name)])
        ?>
        <h5><?= Yii::$app->formatter->asCurrency($model->price, 'EUR') ?></h5>
    </div>
</div>