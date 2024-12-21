<?php

use common\models\Listing;
use yii\helpers\Html;

?>
<div class="product-item bg-light mb-4">
    <div class="product-img position-relative overflow-hidden">
        <?= Html::img($model->image_url, [
            'alt' => html::encode($model->name),
            'class' => 'img-fluid w-100',
        ]); ?>
        <div class="product-action">
            <?= Html::a('<i class="fa fa-shopping-cart"></i>',
                ['/cart/add-to-cart', 'itemId' => $model->id, 'type' => $model instanceof Listing ? 'listing' : 'product'],
                ['class' => 'btn btn-outline-dark btn-square btn-bg-dark']);
            ?>
            <?= Html::a('<i class="fa fa-search"></i>',
                ['/product/view', 'id' => $model->id],
                ['class' => 'btn btn-outline-dark btn-square'])
            ?>
        </div>
    </div>
    <div class="text-center py-4">
        <?= Html::a(html::encode($model->name),
            ['/product/view', 'id' => $model->id],
            ['class' => 'h6 text-decoration-none text-truncate d-block px-3', 'title' => html::encode($model->name)])
        ?>
        <h5><?= Yii::$app->formatter->asCurrency($model->price, 'EUR') ?></h5>
    </div>
</div>
