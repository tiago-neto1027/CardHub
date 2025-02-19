<?php

use yii\helpers\Html;
use yii\widgets\ListView;
use yii\helpers\Url;

$this->title = 'My Yii Application';
?>
<!DOCTYPE html>
<html lang="en">

<body>
<!-- Carousel Start -->
<div class="container-fluid mb-3">
    <div class="row px-xl-5">
        <div id="header-carousel" class="carousel slide carousel-fade mb-30 mb-lg-0 col-lg-8 offset-lg-2"
             data-ride="carousel">
            <ol class="carousel-indicators">
                <li data-target="#header-carousel" data-slide-to="0" class="active"></li>
                <li data-target="#header-carousel" data-slide-to="1"></li>
                <li data-target="#header-carousel" data-slide-to="2"></li>
            </ol>
            <div class="carousel-inner">
                <div class="carousel-item position-relative active" style="height: 430px;">
                    <?= Html::img('@web/img/carousel-1.jpg', [
                        'class' => 'position-absolute w-100 h-100',
                        'style' => 'object-fit: cover;'
                    ]) ?>
                    <div class="carousel-caption d-flex flex-column align-items-center justify-content-center">
                    </div>
                </div>
                <div class="carousel-item position-relative" style="height: 430px;">
                    <?= Html::img('@web/img/carousel-2.jpg', [
                        'class' => 'position-absolute w-100 h-100',
                        'style' => 'object-fit: cover;'
                    ]) ?>
                    <div class="carousel-caption d-flex flex-column align-items-center justify-content-center">
                    </div>
                </div>
                <div class="carousel-item position-relative" style="height: 430px;">
                    <?= Html::img('@web/img/carousel-3.jpg', [
                        'class' => 'position-absolute w-100 h-100',
                        'style' => 'object-fit: cover;'
                    ]) ?>
                    <div class="carousel-caption d-flex flex-column align-items-center justify-content-center">
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-2"></div>
  </div>
</div>

<!-- Products -->
<div class="container-fluid pt-5 pb-3">
    <h2 class="position-relative text-uppercase mx-xl-5 mb-4 border-bottom"><span class="text-light pr-3">Featured Products</span></h2>
    <div class="row px-xl-5">
        <?= ListView::widget([
            'dataProvider' => $products,
            'itemOptions' => ['class' => 'item col-lg-2 col-md-4 col-sm-6 pb-1'],
            'itemView' => '../product/_product',
            'layout' => "<div class='row g-3'>{items}</div>\n{pager}",
        ]); ?>
    </div>
</div>

<!-- Offer Start -->
<div class="container-fluid pt-5 pb-3">
    <div class="row px-xl-5">
        <div class="col-md-6">
            <div class="product-offer mb-30" style="height: 300px;">
                <?= Html::img('@web/img/offer_cards.jpg', [
                    'class' => 'img-fluid w-100 h-100',
                ]) ?>
                <div class="offer-text">
                    <h6 class="text-white text-uppercase">Check our collection of</h6>
                    <h3 class="text-white mb-3">CARDS!</h3>
                    <a href="<?= Url::to(['/card/index'])?>" class="btn btn-primary text-light">Browse</a>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="product-offer mb-30" style="height: 300px;">
                <?= Html::img('@web/img/offer_products.jpg', [
                    'class' => 'img-fluid w-100 h-100',
                ]) ?>
                <div class="offer-text">
                    <h6 class="text-white text-uppercase">Browse our product</h6>
                    <h3 class="text-white mb-3">CATALOG!</h3>
                    <a href="<?= Url::to(['/product/index'])?>" class="btn btn-primary text-light">Browse</a>
                </div>
            </div>
        </div>
    </div>
</div>


<!-- Recent Products -->
<div class="container-fluid pt-5 pb-3">
    <h2 class="position-relative text-uppercase mx-xl-5 mb-4 border-bottom"><span class="text-light pr-3">Recent Products</span>
    </h2>
    <div class="row px-xl-5">
        <?= ListView::widget([
            'dataProvider' => $recentProducts,
            'itemOptions' => ['class' => 'item col-lg-2 col-md-4 col-sm-6 pb-1'],
            'itemView' => '../product/_product',
            'layout' => "<div class='row g-3'>{items}</div>\n{pager}",
        ]); ?>
    </div>
</div>
</body>

</html>