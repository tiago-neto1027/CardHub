<?php
/** @var yii\web\View $this */
use yii\helpers\Html;
use yii\helpers\Url;
use \common\models\Product;
use yii\widgets\ListView;

use function PHPSTORM_META\type;

?>
<!DOCTYPE html>
<html lang="en">
    <body>
        <div class="container-fluid">
        <div class="row">
        <div class="col-lg-3" >
            <div class="container-fluid pt-5 pb-3">
                <h2 class="section-title position-relative text-uppercase mb-4"><span class="bg-secondary pr-3">Filters</span></h2>
                <a class="btn rounded mb-4" href="<?= \yii\helpers\Url::to(['/catalog']) ?>">Clear</a>

                <div class="dropdown">
                    <a class="btn dropdown-toggle mb-4" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Type</a>
                    <div class="dropdown-menu bg-dark">
                        <?php    
                            echo Html::a('Cards', Url::current([
                                'type' => 'card']),
                                ['class' => 'dropdown-item']);
                            echo Html::a('Products', Url::current([
                                'type' => 'product']),
                                ['class' => 'dropdown-item']);
                         ?>  
                    </div>
                </div>
                <div class="filter-buttons border border-dark rounded mb-4" id="filter-buttons">
                    <?php 
                        if ($games = \common\models\Game::getAllGames()):     
                            foreach ($games as $game) {
                            echo Html::a($game->name, Url::current([
                                'id' => $game->id]),
                                ['class' => 'dropdown-item']);
                            }
                        endif; ?>        
                </div>
                <div class="filter-buttons border border-dark rounded mb-4" id="">
                    <?php
                        $productTypeOptions = Product :: getProductTypes();         //TODO fix?
                        if (empty($productTypeOptions)) {
                            echo "<p>No product types available.</p>";
                        } else {
                            foreach ($productTypeOptions as $productType) {
                                echo Html::a($productType->type, Url::current([
                                'productType' => $productType->type]),
                                ['class' => 'dropdown-item']);
                            }
                        }
                    ?>
                </div>
            </div>
        </div>
        
        <div class="col-lg-9">
            <!-- Products Start -->
            <div class="container-fluid pt-5 pb-3">
                <h2 class="section-title position-relative text-uppercase mx-xl-5 mb-4"><span class="bg-secondary pr-3">Product Catalog</span></h2>
                <div class="row px-xl-5">
                    <?php 
                        if (empty($products)) {
                            echo "<p>No products available.</p>";
                        } else {                   
                                foreach ($products as $item): ?>
                            <div class="col-lg-3 col-md-4 col-sm-6 pb-1">
                                <div class="product-item bg-light mb-4">
                                    <div class="product-img position-relative overflow-hidden">
                                        <img class="img-fluid w-100" src="<?= $item->image_url ?>" alt="">
                                        <div class="product-action">
                                            <a class="btn btn-outline-dark btn-square" href=""><i class="fa fa-shopping-cart"></i></a>
                                            <a class="btn btn-outline-dark btn-square" href=""><i class="far fa-heart"></i></a>
                                            <a class="btn btn-outline-dark btn-square" href=""><i class="fa fa-search"></i></a>
                                        </div>
                                    </div>
                                    <div class="text-center py-4">
                                        <a class="h6 text-decoration-none text-truncate d-block" href="<?= \yii\helpers\Url::to(['/catalog/view', 'id' => $item->id, 'type' => $item instanceof Product ? 'product' : 'card'])?>"><?= $item->name ?></a>
                                        <div class="d-flex align-items-center justify-content-center mt-2">
                                            <?php 
                                            if (empty($item->price)) { 
                                                    echo "<h5>No no price available.</h5>";
                                            }  else{?>
                                                <h5>€ <?= $item->price ?></h5><!-- REMOVED old price<h6 class="text-muted ml-2"><del> old price</del></h6>-->
                                            <?php }?>
                                            
                                        </div><!--
                                        <div class="d-flex align-items-center justify-content-center mb-1">
                                            <small class="fa fa-star text-primary mr-1"></small>
                                            <small class="fa fa-star text-primary mr-1"></small>
                                            <small class="fa fa-star text-primary mr-1"></small>
                                            <small class="fa fa-star text-primary mr-1"></small>
                                            <small class="fa fa-star text-primary mr-1"></small>
                                            <small>(99)</small>
                                        </div>-->
                                    </div>
                                </div>
                            </div>
                        <?php endforeach;
                        }
                    ?>
                </div>
            </div>
            <?= ListView::widget([
                'dataProvider' => $dataProvider,
                'itemOptions' => ['class' => 'item col-md-6'],
                'itemView' => '../listing/_listing',
                'layout' => "<div class='row g-3'>{items}</div>\n{pager}"
            ]) ?>
            <!-- Products End -->
        </div>
        </div>
        </div>
        <div class= "d-flex justify-content-center pagination-container mt-4">
            <div class="pagination">
                <!-- Backward Button -->
                <?php if ($page > 1): ?>
                    <a class="btn active rounded" href="?<?= http_build_query(array_merge($_GET, ['page' => $page - 1])) ?>">&laquo;</a>
                <?php else: ?>
                    <span class="btn disabled rounded">&laquo;</span>
                <?php endif; ?>

                <!-- Pagination -->
                <?php 
                $totalPages = ceil($totalCount / $pageSize); 
                for ($i = 1; $i <= $totalPages; $i++): ?>
                    <a class="btn rounded <?= $i === $page ? 'active' : '' ?>" 
                    href="?<?= http_build_query(array_merge($_GET, ['page' => $i])) ?>">
                    <?= $i ?>
                    </a>
                <?php endfor; ?>

                <!-- Forward Button -->
                <?php if ($page < $totalPages): ?>
                    <a class="btn active rounded" href="?<?= http_build_query(array_merge($_GET, ['page' => $page + 1])) ?>">&raquo;</a>
                <?php else: ?>
                    <span class="btn disabled rounded">&raquo;</span>
                <?php endif; ?>
            </div>
        </div>    
    </body>
</html>

