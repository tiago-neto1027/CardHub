<?php

use yii\helpers\Url;
use yii\helpers\Html;

$this->title = $model->name;
?>

<div class="container my-5">
    <div class="card">
        <!-- Card Header -->
        <div class="card-header">
            <h2 class="text-center text-black mb-0"><?= Html::encode($this->title) ?></h2>
        </div>

        <!-- Card Body -->
        <div class="card-body bg-dark">
            <div class="row">
                <!-- Product Image -->
                <div class="col-lg-3 col-md-4">
                    <img class="img-fluid w-100 rounded-3" src="<?= $model->image_url ?>" alt="<?= Html::encode($model->name) ?>">
                </div>

                <!-- Product Details -->
                <div class="col-lg-4 col-md-4">
                    <h4 class="text-primary pt-2"><i class="fas fa-info-circle me-2"></i>Product Details:</h4>
                    <p class="text-light mt-3"><span class="text-primary font-weight-bold">Price: </span>
                        <?php
                        if (empty($model->price)) {
                            echo '<span class="text-secondary">No price available.</span>';
                        } else {
                            echo Yii::$app->formatter->asCurrency($model->price, 'EUR');
                        }
                        ?>
                    </p>

                    <!-- Availability -->
                    <div class="mt-4">
                        <?php if ($model->stock <= 0): ?>
                            <div class="alert alert-warning mb-0">
                                <i class="bi bi-bag-x-fill me-2"></i>Not available
                            </div>
                        <?php else: ?>
                            <div class="alert alert-success mb-0">
                                <i class="bi bi-bag-check-fill me-2"></i>In Stock: <?= $model->stock ?>
                            </div>
                            <?= Html::a(
                                '<i class="fas fa-cart-plus me-2"></i>Add to cart',
                                ['/cart/add-to-cart', 'itemId' => $model->id, 'type' => 'product'],
                                ['class' => 'btn btn-light btn-lg w-100 mt-3']
                            ) ?>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Product Description -->
                <?php if (!empty($model->description)): ?>
                    <div class="col-lg-3 col-md-4 mt-2 offset-lg-2">
                        <h4 class="text-primary pt-2"><i class="fas fa-align-left"></i> Description:</h4>
                        <p class="text-light"><?= nl2br(Html::encode($model->description)) ?></p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<style>
    h4.text-primary, span.text-primary {
        text-shadow: 0.5px 0.5px 1px black, -0.5px -0.5px 1px black;
    }
</style>
