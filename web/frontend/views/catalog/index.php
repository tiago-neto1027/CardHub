<?php
/** @var yii\web\View $this */
?>
<!DOCTYPE html>
<html lang="en">
    <body>
        <div class="container-fluid">
        <div class="row">
        <div class="col-lg-3" >
            <div class="container-fluid pt-5 pb-3">
                <h2 class="section-title position-relative text-uppercase mb-4"><span class="bg-secondary pr-3">Filters</span></h2>
                <a class="btn rounded mb-2" href="\CardHub/web/frontend/web/catalog">Clear</a>
                <div class="filter-buttons mb-2" id="filter-buttons">
                    <button class="btn rounded" onclick="applyFilter('one piece')">One Piece</button>
                    <button class="btn rounded" onclick="applyFilter('magic')">Magic</button>
                    <button class="btn rounded" onclick="applyFilter('pokemon')">Pokemon</button>
                </div>
                <div class="filter-buttons mb-2" id="filter-buttons">
                    <button class="btn rounded" onclick="applyFilter('booster')">Booster</button>
                    <button class="btn rounded" onclick="applyFilter('playmat')">Playmat</button>
                    <button class="btn rounded" onclick="applyFilter('sleeve')">Sleeve</button>
                    <button class="btn rounded" onclick="applyFilter('shirt')">T-Shirt</button>
                </div>
                <script>
                    function applyFilter(filterWord) {
                        const url = new URL(window.location.href);      //TODO remove and apply in main.js
                        url.searchParams.set('filter', filterWord);     // Set or update the filter query parameter
                        window.location.href = url.toString();          // Redirect to the updated URL
                    } 
                </script>
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

                            $count=0;
                            $maxcard=12;

                        foreach($products as $product){
                            if ($count >= $maxcard) break;      //COUNTER is used to limit the max number of cards. Default set to 8.
                            
                    ?>
                            <div class="col-lg-3 col-md-4 col-sm-6 pb-1">
                                <div class="product-item bg-light mb-4">
                                    <div class="product-img position-relative overflow-hidden">
                                        <img class="img-fluid w-100" src="<?= $product->image_url ?>" alt="">
                                        <div class="product-action">
                                            <a class="btn btn-outline-dark btn-square" href=""><i class="fa fa-shopping-cart"></i></a>
                                            <a class="btn btn-outline-dark btn-square" href=""><i class="far fa-heart"></i></a>
                                            <a class="btn btn-outline-dark btn-square" href=""><i class="fa fa-sync-alt"></i></a>
                                            <a class="btn btn-outline-dark btn-square" href=""><i class="fa fa-search"></i></a>
                                        </div>
                                    </div>
                                    <div class="text-center py-4">
                                        <a class="h6 text-decoration-none text-truncate d-block" href="catalog/view?id=<?= $product->id ?>"><?= $product->name ?></a>
                                        <div class="d-flex align-items-center justify-content-center mt-2">
                                            <h5>€ <?= $product->price ?></h5><!-- REMOVED old price<h6 class="text-muted ml-2"><del> old price</del></h6>-->
                                        </div>
                                        <div class="d-flex align-items-center justify-content-center mb-1">
                                            <small class="fa fa-star text-primary mr-1"></small>
                                            <small class="fa fa-star text-primary mr-1"></small>
                                            <small class="fa fa-star text-primary mr-1"></small>
                                            <small class="fa fa-star text-primary mr-1"></small>
                                            <small class="fa fa-star text-primary mr-1"></small>
                                            <small>(99)</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                    <?php
                        $count++;}}
                    ?>
                </div>
            </div>
            <!-- Products End -->
        </div>
        </div>
        </div>
        <div class= "d-flex justify-content-center pagination-container mt-4">
            <div class="pagination">
                <a class="btn active rounded" href="?<?= http_build_query(array_merge($_GET, ['page' => $page - 1])) ?>">&laquo;</a>

                <?php           // Pagination Buttons
                $totalPages = ceil($totalCount / $pageSize); 
                for($i = 1; $i <=$totalPages; $i++){ ?>
                    <a class="btn rouded" href="?<?= http_build_query(array_merge($_GET, ['page' => $i])) ?>">
                    <?= $i ?></a> <?php
                }?>
                
                <a class="btn active rounded" href="?<?= http_build_query(array_merge($_GET, ['page' => $page + 1])) ?>">&raquo;</a> 
            </div>
        </div>    
    </body>
</html>

