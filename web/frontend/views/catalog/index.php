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
        <div class="col-lg-3">
            <div class="container-fluid pt-5 pb-3">
                <h2 class="section-title position-relative text-uppercase mb-4"><span
                            class="bg-secondary pr-3">Filters</span></h2>
    
                <?php
                    if($type === 'product')
                    {
                        ?><a class="btn bg-primary text-dark rounded mb-4" href="<?= \yii\helpers\Url::to(['/catalog/index?type=product']) ?>">Clear</a><?php   
                    }elseif($type === 'listing'){
                        ?><a class="btn bg-primary text-dark rounded mb-4" href="<?= \yii\helpers\Url::to(['/catalog/index?type=listing']) ?>">Clear</a><?php
                    }
                ?>

                <div class="dropdown">
                    <a class="btn dropdown-toggle mb-2" type="button" id="dropdownMenuButton" data-toggle="dropdown"
                       aria-haspopup="true" aria-expanded="false">Type</a>
                    <div class="dropdown-menu bg-dark">
                        <?php    
                            echo Html::a('Cards', Url::current([
                                'type' => 'listing']),
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
                
            </div>
            <div>
            <?php use yii\widgets\ActiveForm; ?>

                <div class="search-form">
                    <?php 
                    if($type === 'product')
                    {
                            $form = ActiveForm::begin([
                            'method' => 'get',
                        ]); ?>

                        <?= $form->field($searchModel, 'name')->textInput(['placeholder' => 'Search by name']) ?>
                        <?= 
                            $form->field($searchModel, 'type')->dropDownList(
                                $productTypes, // Array of product types, e.g., ['type1' => 'Type 1', 'type2' => 'Type 2']
                                ['prompt' => 'Select Product Type'],
                                

                            ) 
                        ?>

                        <div class="form-group">
                            <?= \yii\helpers\Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
                        </div>

                        <?php ActiveForm::end(); 

                    }elseif($type === 'listing'){
                        $form = ActiveForm::begin([
                            'method' => 'get',
                        ]); ?>

                        <?php ActiveForm::end();
                    }
                    ?>  
                </div>
            </div>
        </div>
        <div class="col-lg-9">
            <div class="container-fluid pt-5 pb-3">
                <h2 class="section-title position-relative text-uppercase mx-xl-2 mb-4">
                    <span class="bg-secondary pr-3">Product Catalog</span></h2>
                <div class="row">
                        <?php 
                            if($type === 'listing')
                            {
                                echo ListView::widget([
                                    'dataProvider' => $dataProvider,
                                    'itemOptions' => ['class' => 'item col-lg-2 col-md-4 col-sm-6 pb-1'],
                                    'itemView' => '../listing/_listing',
                                    'layout' => "<div class='row g-3'>{items}</div>\n{pager}",
                                    //pager options
                                    'pager' => [
                                        'class' => \yii\bootstrap5\LinkPager::class,
                                        'options' => [
                                            'class' => 'pagination justify-content-center ',
                                        ],
                                        'linkOptions' => [
                                            'class' => 'btn rounded bg-primary text-dark mr-1',
                                        ],
                                        'prevPageLabel' => '<',
                                        'nextPageLabel' => '>',
                                        'maxButtonCount' => 5,
                                        'pagination' => ['params' => ['main-page' => true], 
                                        ],
                                    ],
                                ]);
                            }
                            elseif($type === 'product')
                            {
                                echo ListView::widget([
                                    'dataProvider' => $dataProvider,
                                    'itemOptions' => ['class' => 'item col-lg-3 col-md-4 col-sm-6 pb-1'],
                                    'itemView' => '../product/_product',
                                    'layout' => "<div class='row g-3'>{items}</div>\n{pager}",
                                    //pager options
                                    'pager' => [
                                        'class' => \yii\bootstrap5\LinkPager::class,
                                        'options' => [
                                            'class' => 'pagination justify-content-center ',
                                        ],
                                        'linkOptions' => [
                                            'class' => 'btn rounded bg-primary text-dark mr-1',
                                        ],
                                        'prevPageLabel' => '<',
                                        'nextPageLabel' => '>',
                                        'maxButtonCount' => 5,
                                        'pagination' => ['params' => ['main-page' => true], 
                                        ],
                                    ],
                                ]);
                            }
                        ?>
                </div>      
            </div>
        </div>
    </div>
</div>
</body>
</html>

