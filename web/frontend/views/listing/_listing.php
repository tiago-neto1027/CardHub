<?php

use common\models\Listing;
use yii\helpers\Html;
use yii\helpers\Url;

?>
<div class="product-item bg-light mb-4 ">
    <div class="row g-0">
        <div class="col-md-4 d-flex align-items-stretch">
            <?= Html::img($model->card->image_url, [
                'alt' => $model->card->name,
                'class' => 'img-fluid rounded-start card-img',
            ]); ?>
        </div>

        <div class="col-md-8 d-flex">
            <div class="card-body d-flex flex-column justify-content-between h-100">
                <h5 class="card-title"><?= Html::encode($model->card->name) ?></h5>
                <p class="card-text"><?= Html::encode($model->card->description) ?></p>
                <p class="card-text"><strong>Condition: </strong><?= Html::encode($model->condition) ?></p>
                <p class="card-text"><strong>Price: </strong><?= number_format($model->price, 2) ?> €</p>

                <div class="col-md-8 d-flex  product-action mt-auto">
                    <?php if($model->status === "inactive"):?>
                        <?=Html::a(
                            '<i class="fa fa-shopping-cart"></i>',
                            null,
                            [
                                'class' => 'btn btn-square disabled',
                                'style' => 'background-color: #343a40; color: #fff; pointer-events: none;',
                                'aria-disabled' => 'true',
                            ]
                        ) ;?>
                    <?php else: ?>
                        <?= Html::a('<i class="fa fa-shopping-cart"></i>', ['/cart/add-to-cart', 'itemId' => $model->id, 'type' => $model instanceof Listing ? 'listing' : 'product'], ['class' => 'btn btn-outline-dark btn-square btn-bg-dark']); ?>
                    <?php endif; ?>
                    <?= Html::a('<i class="far fa-heart"></i>', ['/favorites/index'], ['class' => 'btn btn-outline-dark btn-square']) ?>
                    <?= Html::a('<i class="fa fa-search"></i>', ['/listing/view', 'id' => $model->id], ['class' => 'btn btn-outline-dark btn-square']) ?>
                </div>
            </div>
        </div>
    </div>
    <div class="position-absolute end-0 p-2" style="top: 90%; transform: translateY(-50%);">
        <?php if (Yii::$app->controller->id === 'listing' && Yii::$app->controller->action->id === 'index') {?>
        <?= Html::a(
            '<i class="fas fa-trash-alt" style="font-size: 1.5rem;"></i>',
            ['listing/delete', 'id' => $model->id],
            [
                'class' => 'text-danger',
                'data' => [
                    'confirm' => 'Are you sure you want to delete this item?',
                    'method' => 'post',
                ],
            ]
        );} ?>
    </div>
</div>
<style>


    .row.g-0 {
        display: flex;
        height: 100%;
    }

    .col-md-4, .col-md-8 {
        display: flex;
        height: 100%;
    }

    .product-item {
        height: 200px; 
        display: flex;
        flex-direction: column;
    }

</style>