<?php

use yii\helpers\Html;
use \common\models\Product;

?>

        <div class="product-item bg-light mb-4">
            <div class="product-img position-relative overflow-hidden">
                <img class="img-fluid w-100" src="<?= $model->image_url ?>" alt="">
                <div class="product-action">
                    <a class="btn btn-outline-dark btn-square" href=""><i class="fa fa-shopping-cart"></i></a>
                    <a class="btn btn-outline-dark btn-square" href=""><i class="far fa-heart"></i></a>
                    <a class="btn btn-outline-dark btn-square" href=""><i class="fa fa-search"></i></a>
                </div>
            </div>
            <div class="text-center py-4">
                <a class="h6 text-decoration-none text-truncate d-block" href="<?= \yii\helpers\Url::to(['/catalog/view', 'id' => $model->id, 'type' => $model instanceof Product ? 'product' : 'card'])?>"><?= $model->name ?></a>
                <div class="d-flex align-items-center justify-content-center mt-2">
                    <?php 
                    if (empty($model->price)) { 
                            echo "<h5>No price available.</h5>";
                    }  else{?>
                        <h5><?= $model->price ?>€</h5>
                    <?php }?>
                    
                </div>
            </div>
        </div>
