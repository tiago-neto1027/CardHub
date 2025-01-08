<?php

use yii\helpers\Url;
$this->title="";
?>
<div class="container-fluid">
    <!-- Summary -->
    <h3 class="border-bottom border-primary">Summary</h3>
    <div class="row mt-2 mb-4">
        <div class="col-12 col-sm-6 col-md-3">
            <?= \hail812\adminlte\widgets\InfoBox::widget([
                'text' => 'Registered Users',
                'number' => $registeredUsers,
                'icon' => 'fas fa-users',
            ]) ?>
        </div>
        <div class="col-12 col-sm-6 col-md-3">
            <?= \hail812\adminlte\widgets\InfoBox::widget([
                'text' => 'Sold products',
                'number' => $soldProducts,
                'icon' => 'fas fa-shopping-cart',
            ]) ?>
        </div>
        <div class="col-12 col-sm-6 col-md-3">
            <?= \hail812\adminlte\widgets\InfoBox::widget([
                'text' => 'Sold Cards',
                'number' => $soldListings,
                'icon' => 'fas fa-clone',
            ]) ?>
        </div>
        <div class="col-12 col-sm-6 col-md-3">
            <?= \hail812\adminlte\widgets\InfoBox::widget([
                'text' => 'Revenue Generated',
                'number' => $revenueGenerated,
                'icon' => 'fas fa-dollar-sign',
            ]) ?>
        </div>
    </div>
    <!-- Summary -->

    <!-- Priorities Overview -->
    <h3 class="border-bottom border-primary">Priorities Overview</h3>
    <div class="row mt-2 mb-4">
        <div class="col-md-4 col-sm-6 col-12">
            <?= \hail812\adminlte\widgets\SmallBox::widget([
                'title' => $pendingCards,
                'text' => 'Pending Cards',
                'icon' => 'fas fa-clone',
                'theme' => 'warning',
                'linkText' => 'Check cards',
                'linkUrl' => Url::to(['card/pending-approval']),
            ]) ?>
        </div>
        <div class="col-md-4 col-sm-6 col-12">
            <?= \hail812\adminlte\widgets\SmallBox::widget([
                'title' => $lowStockProducts,
                'text' => 'Low Stock Products',
                'icon' => 'fas fa-shopping-cart',
                'theme' => 'warning',
                'linkText' => 'Check products',
                'linkUrl' => Url::to(['product/low-stock']),
            ]) ?>
        </div>
        <?php if($noStockProducts > 0): ?>
        <div class="col-md-4 col-sm-6 col-12">
            <?= \hail812\adminlte\widgets\SmallBox::widget([
                'title' => $noStockProducts,
                'text' => 'Out of Stock Products',
                'icon' => 'fas fa-shopping-cart',
                'theme' => 'danger',
                'linkText' => 'Check products',
                'linkUrl' => Url::to(['product/no-stock']),
            ]) ?>
        </div>
        <?php else: ?>
            <div class="col-md-4 col-sm-6 col-12">
                <?php $smallBox = \hail812\adminlte\widgets\SmallBox::begin([
                    'title' => $noStockProducts,
                    'text' => 'Out of Stock Products',
                    'icon' => 'fas fa-shopping-cart',
                    'linkText' => 'Check products',
                    'linkUrl' => Url::to(['product/no-stock']),
                ]) ?>
                <?= \hail812\adminlte\widgets\Ribbon::widget([
                    'id' => $smallBox->id.'-ribbon',
                    'text' => 'No Products',
                    'theme' => 'success',
                    'size' => 'lg',
                    'textSize' => 'sm'
                ]) ?>
                <?php \hail812\adminlte\widgets\SmallBox::end() ?>
            </div>
        <?php endif;?>
    </div>
    <!-- Priorities Overview -->

    <!-- Profits -->
    <h3 class="border-bottom border-primary">Monthly Profits</h3>
    <div class="row mt-2 mb-4">
        <div class="col-lg-3 col-md-6 col-12">
            <?= \hail812\adminlte\widgets\InfoBox::widget([
                'text' => 'Card Profit in ' . $previousMonthName,
                'number' => '+ ' . Yii::$app->formatter->asDecimal($cardProfitLastMonth, 2) . '€',
                'icon' => 'fas fa-clone',
                'progress' => [
                    'width' => '100%',
                    'description' => 'Sold ' .
                        Yii::$app->formatter->asDecimal($cardProfitLastMonth, 2) .
                        '€ worth of cards',
                ]
            ]) ?>
        </div>
        <div class="col-lg-3 col-md-6 col-12">
            <?= \hail812\adminlte\widgets\InfoBox::widget([
                'text' => 'Card Profit in ' . $twoMonthsAgoName,
                'number' => '+ ' . Yii::$app->formatter->asDecimal($cardProfitTwoMonthsAgo, 2) . '€',
                'icon' => 'fas fa-clone',
                'progress' => [
                    'width' => '100%',
                    'description' => 'Sold ' .
                        Yii::$app->formatter->asDecimal($cardProfitTwoMonthsAgo, 2) .
                        '€ worth of cards',
                ]
            ]) ?>
        </div>
        <div class="col-lg-3 col-md-6 col-12">
            <?= \hail812\adminlte\widgets\InfoBox::widget([
                'text' => 'Product Profit in ' . $previousMonthName,
                'number' => '+ ' . Yii::$app->formatter->asDecimal($productProfitLastMonth, 2) . '€',
                'icon' => 'fas fa-shopping-cart',
                'progress' => [
                    'width' => '100%',
                    'description' => 'Sold ' .
                        Yii::$app->formatter->asDecimal($productProfitLastMonth, 2) .
                        '€ worth of products',
                ]
            ]) ?>
        </div>
        <div class="col-lg-3 col-md-6 col-12">
            <?= \hail812\adminlte\widgets\InfoBox::widget([
                'text' => 'Product Profit in ' . $twoMonthsAgoName,
                'number' => '+ ' . Yii::$app->formatter->asDecimal($productProfitTwoMonthsAgo, 2) . '€',
                'icon' => 'fas fa-shopping-cart',
                'progress' => [
                    'width' => '100%',
                    'description' => 'Sold ' .
                        Yii::$app->formatter->asDecimal($productProfitTwoMonthsAgo, 2) .
                        '€ worth of products',
                ]
            ]) ?>
        </div>
    </div>
    <!-- Profits -->
</div>