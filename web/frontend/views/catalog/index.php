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
        <!-- Filters -->
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
        <!-- Products-->
        <div class="col-lg-9">
            <div class="container-fluid pt-5 pb-3">
                <h2 class="section-title position-relative text-uppercase mx-xl-2 mb-4"><span class="bg-secondary pr-3">Product Catalog</span></h2>
                <div class="row">
                        <?php 
                            if($type === 'card')
                            {
                                echo ListView::widget([
                                    'dataProvider' => $dataProvider,
                                    'itemOptions' => ['class' => 'item col-md-6 col-sm-6 pb-1'],
                                    'itemView' => '../listing/_listing',
                                    'layout' => "<div class='row g-3'>{items}</div>\n{pager}",
                                ]);
                            }
                            elseif($type === 'product')
                            {
                                echo ListView::widget([
                                    'dataProvider' => $dataProvider,
                                    'itemOptions' => ['class' => 'item col-lg-2 col-md-4 col-sm-6 pb-1'],
                                    'itemView' => '../product/_product',
                                    'layout' => "<div class='row g-3'>{items}</div>\n{pager}",
                                ]);
                            }
                        ?>
                </div>      
            </div>
        </div>
    </body>
</html>

