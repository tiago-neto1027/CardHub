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
    <h1 class="text-center mb-4 text-light"><?= Html::encode($this->title) ?></h1>

    <div class="container-fluid">
        <div class="row">
            <!-- Listings Table -->
            <div class="col-lg-8 mb-4">
                <div class="card shadow-sm">
                    <div class="card-header bg-light text-dark">
                        <h4 class="mb-0"><i class="fas fa-list-alt me-2"></i> Active Listings</h4>
                    </div>
                    <div class="card-body p-0">
                        <table class="table table-dark table-hover mb-0">
                            <thead class="bg-light">
                            <tr>
                                <th class="text-primary">Listing</th>
                                <th class="text-primary">Seller</th>
                                <th class="text-primary">Condition</th>
                                <th class="text-primary">Price</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($listings as $listing): ?>
                                <?php if ($listing->status === "active"): ?>
                                    <tr>
                                        <td>
                                            <?= Html::a(
                                                'View Listing <i class="fas fa-external-link-alt ms-2"></i>',
                                                ['listing/view', 'id' => $listing->id],
                                                ['class' => 'text-info text-decoration-none']
                                            ) ?>
                                        </td>
                                        <td><?= Html::encode($listing->seller->username) ?></td>
                                        <td><?= Html::encode($listing->condition) ?></td>
                                        <td><?= number_format($listing->price, 2) ?>€</td>
                                    </tr>
                                <?php endif; ?>
                            <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Card Details -->
            <div class="col-lg-4">
                <div class="card shadow-sm">
                    <div class="card-header bg-light">
                        <h4 class="mb-0"><i class="fas fa-info-circle me-2"></i> Card Details</h4>
                    </div>
                    <div class="card-body bg-dark">
                        <!-- Card Image -->
                        <div class="text-center mb-4">
                            <img src="<?= $model->image_url ?>" alt="<?= Html::encode($model->name) ?>" class="img-fluid rounded-3 shadow">
                        </div>

                        <!-- Description -->
                        <?php if (!empty($model->description)): ?>
                            <div class="mb-4">
                                <h5 class="text-primary"><i class="fas fa-align-left me-2"></i>Description</h5>
                                <p class="text-light"><?= nl2br(Html::encode($model->description)) ?></p>
                            </div>
                        <?php endif; ?>

                        <!-- Available Listings -->
                        <div class="mb-4">
                            <h5 class="text-primary"><i class="fas fa-box-open me-2"></i>Available Listings</h5>
                            <p class="text-light"><?= $availableListingsCount ?></p>
                        </div>

                        <!-- Favorite Button -->
                        <div class="text-center">
                            <?php if (!$model->isFavorited()): ?>
                                <?= Html::a(
                                    '<i class="fas fa-heart me-2 text-light"></i>Add to Favorites',
                                    ['/favorite/create', 'id' => $model->id],
                                    ['class' => 'btn btn-outline-danger w-100 text-light']
                                ) ?>
                            <?php else: ?>
                                <?= Html::a(
                                    '<i class="fas fa-heart-broken me-2 text-light"></i>Remove from Favorites',
                                    ['/favorite/remove', 'id' => $model->id],
                                    ['class' => 'btn btn-outline-danger w-100 text-light']
                                ) ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .card-view {
        padding: 20px;
    }

    .card {
        border: none;
        border-radius: 10px;
    }

    .card-header {
        border-radius: 10px 10px 0 0;
    }

    .table-hover tbody tr:hover {
        background-color: #f8f9fa;
    }

    .text-info {
        color: #17a2b8 !important;
    }

    .btn-outline-danger {
        border-color: #dc3545;
        color: #dc3545;
    }

    .btn-outline-danger:hover {
        background-color: #dc3545;
        color: white;
    }

    .img-fluid {
        max-height: 300px;
        width: auto;
    }
</style>