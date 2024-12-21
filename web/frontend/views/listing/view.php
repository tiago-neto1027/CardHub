<?php

use yii\helpers\Url;
use yii\helpers\Html;

$this->title = $model->card->name;
?>


<!DOCTYPE html>
<html lang="en">
<body>
<div class="container-fluid pt-5 pb-3">
    <div class="row px-xl-5">
        <div class="container bg-dark p-3 col-lg-3 rounded-3">
            <div class="product-img position-relative overflow-hidden">
                <img class="img-fluid w-100 rounded-3" src="<?= $model->card->image_url ?>" alt="">
            </div>
        </div>
        <div class="col-lg-5">
            <h4 class="text-secondary mb-4 mt-2"><?= $model->card->name ?></h4>
            <div class="container-fluid bg-dark mt-4 mb-4 rounded-1" style="padding: 10px">
                <h5 class="text-secondary"><?= nl2br(Html::encode($model->card->description)) ?></h5>
            </div>
            <h3 class="text-primary mb-4"><?= Yii::$app->formatter->asCurrency($model->price, 'EUR') ?></h3>
            <div>
                <?php
                if ($model->status === "inactive") { ?>
                    <div class=" align-items-left">
                        <h5 class="text-secondary mb-4"><i class="text-primary bi bi-bag-x-fill me-2"></i>Not available
                        </h5>
                        <button type="button" class="rounded bg-primary text-secondary btn btn-lg disabled">Add to
                            cart!
                        </button>
                    </div><?php
                } elseif ($model->status === "active") { ?>
                    <div class=" align-items-left">

                    <h5 class="text-secondary mb-4"><i class="text-primary bi bi-bag-check-fill me-2"></i>Available!
                    </h5>
                    <?php
                    if ($model->seller_id != Yii::$app->user->identity->id)
                        echo Html::a('Add to cart!', ['/cart/add-to-cart', 'itemId' => $model->id, 'type' => 'listing'], [
                            'class' => 'btn btn-primary btn-lg rounded',]) ?>
                    </div><?php
                } ?>
            </div>
        </div>
        <div class="col-lg-4">
        </div>
    </div>
</div>
</body>



