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
            <div class="col-lg-8 border p-3">
                <?php foreach ($listings as $listing): ?>
                    <p><strong>Price:</strong> <?= Yii::$app->formatter->asCurrency($listing->price) ?></p>
                <?php endforeach; ?>
            </div>
            <div class="col-4">
                <div class="row">
                    <div class="col-lg-7 bg-dark">
                        <div class="p-3 text-secondary">
                            <p><strong>Description: </strong><?= $model->description ?></p>
                            <p><strong>Available Listings:</strong> <?= $availableListingsCount ?></p>
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
