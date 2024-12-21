<?php

use common\models\Listing;
use yii\helpers\Html;
use yii\helpers\Url;

?>
<div class="col-12 mt-3">
    <div class="container-fluid">
        <div class="product-item bg-light mb-4">
            <div class="row g-0">
                <!-- Image column (will fill the full height) -->
                <div class="col-md-4 d-flex align-items-stretch p-0">
                    <?= Html::img($model->card->image_url, [
                        'alt' => $model->card->name,
                        'class' => 'img-fluid rounded-start card-img h-100 w-100 object-cover',
                    ]); ?>
                </div>

                <!-- Text column with card title and description -->
                <div class="col-md-8 d-flex">
                    <div class="card-body d-flex flex-column justify-content-between h-100">
                        <h5 class="card-title"><?= Html::encode($model->card->name) ?></h5>
                        <p class="card-text"><?= nl2br(Html::encode($model->card->description)) ?></p>

                        <!-- Buttons below the card content -->
                        <div class="d-flex justify-content-between mt-3">
                            <!-- Remove Button with Heart icon -->
                            <?= Html::a(
                                '<i class="fas fa-heart"></i> Remove', // Heart icon and text
                                ['remove', 'id' => $model->card->id],
                                [
                                    'class' => 'btn btn-warning btn-sm', // Yellow button color
                                    'title' => 'Remove from cart', // Tooltip text
                                    'data-toggle' => 'tooltip', // Enable Bootstrap tooltip
                                ]
                            ) ?>

                            <!-- View Button (Items for Sale) -->
                            <?= Html::a(
                                'Items for Sale', // Button text
                                ['card/view', 'id' => $model->card->id],
                                ['class' => 'btn btn-info btn-sm text-light'] // Styling for View button
                            ) ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
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