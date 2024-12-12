<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

?>

<!DOCTYPE html>
<html lang="en">
    <body>
        <div class="container-fluid pt-5 pb-3">
            <h2 class="section-title position-relative text-uppercase mx-xl-5 mb-4"><span class="bg-secondary pr-3">Product</span></h2>
            <div class="row px-xl-5">
                <div class="container bg-dark p-3 col-lg-5 rounded-3">
                    <div class="product-img position-relative overflow-hidden">
                    <img class="img-fluid w-100 rounded-3" src="<?= $model->image_url ?>" alt="">
                    </div>
                </div>
                <div class="col-lg-7">
                   <h4 class="text-secondary mb-4 mt-2"><?= $model->name ?></h4>
                   <h3 class="text-primary mb-4"><?= $model->price ?>€</h3>
                   
                   <div>
                    <?php
                        if($model->stock > 0){
                            ?>
                            <div class=" align-items-left">

                                <h5 class="text-secondary mb-4"><i class="text-primary bi bi-bag-check-fill me-2"></i>Em Stock: <?= $model->stock ?></h5> 
                                <button type="button" class="rounded bg-primary text-secondary btn btn-lg">Add to cart!</button>
                            </div><?php
                            
                        } else{
                            ?> 
                            <div class=" align-items-left">
                                
                                <h5 class="text-secondary mb-4"><i class="text-primary bi bi-bag-x-fill me-2"></i>Sem Stock!</h5>
                                <button type="button" class="rounded bg-primary text-secondary btn btn-lg disabled">Add to cart!</button>
                            </div><?php
                        }
                        	?>
                   </div>
                   <div class="container-fluid bg-dark mt-4 mb-4 rounded-1" style="padding: 10px">
                        <h5 class="text-secondary"><?= $model->description ?></h5>
                   </div>
                   
                </div>
            </div>
        </div>
    </body>




