<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var common\models\Card $model */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Cards', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="card-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="container-fluid pt-5 pb-3">
        <div class="row px-xl-5">
            <div class="col-lg-8">
                <table class="table table-dark">
                    <thead>
                    <tr>
                        <th class="text-info">Listing</th>
                        <th class="text-info">Seller Username</th>
                        <th class="text-info">Condition</th>
                        <th class="text-info">Price</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($listings as $listing): ?>
                        <?php if($listing->status === "active"): ?>
                        <tr>
                            <td>
                                <a href="<?= Yii::$app->urlManager->createUrl(['listing/view', 'id' => $listing->id]) ?>" class="text-info">
                                    View Listing
                                </a>
                            </td>
                            <td><?= Html::encode($listing->seller->username) ?></td>
                            <td><?= Html::encode($listing->condition) ?></td>
                            <td><?= number_format($listing->price, 2) ?>€</td>
                        </tr>
                        <?php endif ?>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <div class="col-4">
                <div class="row">
                    <div class="col-lg-7 bg-dark">
                        <div class="p-3 text-secondary">
                            <?php if (!empty($model->description)): ?>
                                <p><strong class="text-info">Description: </strong><?= nl2br(Html::encode($model->description)) ?></p>
                            <?php endif; ?>
                            <p><strong class="text-info">Available Listings:</strong> <?= $availableListingsCount ?></p>

                            <!-- Favorite Button -->
                            <div class="mt-2">
                                <?php if (!$model->isFavorited()): ?>
                                    <?= Html::a('<i class="fas fa-heart" id="favorite-heart"></i> Add to Favorites',
                                        ['/favorite/create', 'id' => $model->id],
                                        ['class' => 'btn btn-outline-info text-light']
                                    ) ?>
                                <?php else: ?>
                                    <?= Html::a('<i class="fas fa-heart-broken"></i> Remove from Favorites',
                                        ['/favorite/remove', 'id' => $model->id],
                                        ['class' => 'btn btn-outline-danger text-light']
                                    ) ?>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <div class="container bg-dark p-3 col-lg-5">
                        <div class="product-img position-relative overflow-hidden">
                            <img class="img-fluid rounded-3" src="<?= $model->image_url ?>" alt="">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
