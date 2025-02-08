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
                <h2 class="position-relative text-uppercase mb-4 border-bottom"><span class="text-light pr-3">Filters</span></h2>

                <div class="mb-3">
                    <label style="color: white;">Catalog</label>
                    <select id="catalog-dropdown" class="form-control">
                        <option value="<?= Url::current(['type' => 'listing']) ?>" <?= $type === 'listing' ? 'selected' : '' ?>>Cards</option>
                        <option value="<?= Url::current(['type' => 'product']) ?>" <?= $type === 'product' ? 'selected' : '' ?>>Products</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label style="color: white;">Game</label>
                    <select id="game-dropdown" class="form-control">
                        <?php foreach ($games as $game): ?>
                            <?php $gameName = Html::encode($game->name); ?>
                            <option value="<?= Url::to(['/catalog/index', 'game' => $game->name, 'type' => $type], false) ?>"
                                <?= isset($_GET['game']) && urldecode($_GET['game']) === $game->name ? 'selected' : '' ?>>
                                <?= $gameName ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <script>
                    document.getElementById('catalog-dropdown').addEventListener('change', function() {
                        if (this.value) {
                            window.location.href = this.value;
                        }
                    });

                    document.getElementById('game-dropdown').addEventListener('change', function() {
                        if (this.value) {
                            window.location.href = this.value;
                        }
                    });
                </script>
            </div>
        </div>

        <!-- CATALOG -->
        <div class="col-lg-9">
            <div class="container-fluid pt-5 pb-3">
                <h2 class="position-relative text-uppercase mb-4 border-bottom"><span class="text-light pr-3">Product Catalog</span></h2>
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

