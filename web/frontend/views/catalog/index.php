<?php
/** @var yii\web\View $this */

use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use \common\models\Product;
use yii\widgets\ListView;
use yii\widgets\ActiveForm;

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
                    <a class="btn dropdown-toggle mb-3" type="button" id="dropdownMenuButton" data-toggle="dropdown"
                       aria-haspopup="true" aria-expanded="false">Catalog</a>
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
                
                <div class="search-form" id="filter-buttons">
                    <div>
                        <?php $gameList = ArrayHelper::map($games, 'id', 'name'); ?>
                        <?php $form = ActiveForm::begin(['method' => 'get',]); ?>
                        <?=
                        $form->field($searchModel, 'game')->dropDownList(
                            $gameList,
                            ['prompt' => 'Select Game',]
                        )?>
                        <?php ActiveForm::end(); ?>
                    </div>
                    <div>
                        <?php
                        if($type === 'product')
                        {
                            $form = ActiveForm::begin(['method' => 'get',]); ?>
                            <?=
                            $form->field($searchModel, 'type')->dropDownList(
                                $productTypes,
                                ['prompt' => 'Select Product Type',]
                            )?>
                            <?= $form->field($searchModel, 'name')->textInput(['placeholder' => 'Search by name']) ?>

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
                                    'itemView' => function ($model, $key, $index, $widget) use ($isActiveView) {
                                        return $this->render('../listing/_listing', [
                                            'model' => $model,
                                            'isActiveView' => $isActiveView,
                                        ]);
                                    },
                                    'layout' => "<div class='row g-3'>{items}</div>\n{pager}",
                                    'pager' => [
                                        'class' => \yii\bootstrap5\LinkPager::class,
                                        'options' => [
                                            'class' => 'pagination justify-content-center',
                                        ],
                                        'linkOptions' => [
                                            'class' => 'btn rounded bg-primary text-dark mr-1',
                                        ],
                                        'prevPageLabel' => '<',
                                        'nextPageLabel' => '>',
                                        'maxButtonCount' => 5,
                                        'pagination' => [
                                            'params' => ['main-page' => true],
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

