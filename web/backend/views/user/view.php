<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var common\models\User $model */

$this->title = $model->username;
$this->params['breadcrumbs'][] = ['label' => 'Users', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="user-view">

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?php
            if($model->status != 0){
                echo Html::a('Delete', ['delete', 'id' => $model->id], [
                    'class' => 'btn btn-danger',
                    'data' => [
                        'confirm' => 'Are you sure you want to delete this item?',
                        'method' => 'post',
                    ],
                ]);
            }
        ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'username',
            'email:email',
            [
                'attribute' => 'role',
                'value' => function ($model) {
                    return $model->getRole();
                },
                'label' => 'User Type',
            ],
            [
                'attribute' => 'status',
                'value' => function ($model) {
                    $statuses = [
                        10 => 'Active',
                        9 => 'Inactive',
                        0 => 'Deleted',
                    ];

                    return $statuses[$model->status] ?? 'Unknown';
                },
                'label' => 'Status',
            ],
            [
                'attribute' => 'created_at',
                'format' => ['date', 'php:d/m/Y'],
                'value' => function ($model) {
                    return $model->created_at;
                },
                'label' => 'Created At',
            ],
            [
                'attribute' => 'updated_at',
                'format' => ['date', 'php:d/m/Y'],
                'value' => function ($model) {
                    return $model->updated_at;
                },
                'label' => 'Updated At',
            ],
        ],
    ]) ?>

    <?php if ($model->getRole() === 'seller'): ?>
        <h3>Seller Summary</h3>
        <table class="table table-bordered">
            <tr>
                <th>Total Listings</th>
                <td><?= $model->getListingsCount() ?></td>
            </tr>
            <tr>
                <th>Active Listings</th>
                <td><?= $model->getListings() ?></td>
            </tr>
            <tr>
                <th>Inactive Listings</th>
                <td><?= $model->getSoldListingsCount() ?></td>
            </tr>
            <tr>
                <th>Total Revenue</th>
                <td><?= $model->getRevenue() ?>€</td>
            </tr>
        </table>

        <h3>Seller Listings</h3>
        <div class="row">
            <div class="col-md-6">
                <h4>Active Listings</h4>
                <?php
                $activeListings = \common\models\Listing::find()
                    ->where(['seller_id' => $model->id, 'status' => 'active'])
                    ->all();

                if (!empty($activeListings)): ?>
                    <ul>
                        <?php foreach ($activeListings as $listing): ?>
                            <li><?= Html::encode($listing->card->name) ?> - <?= number_format($listing->price, 2) ?>€</li>
                        <?php endforeach; ?>
                    </ul>
                <?php else: ?>
                    <p>No active listings.</p>
                <?php endif; ?>
            </div>
            <div class="col-md-6">
                <h4>Inactive Listings</h4>
                <?php
                $inactiveListings = \common\models\Listing::find()
                    ->where(['seller_id' => $model->id, 'status' => 'inactive'])
                    ->all();

                if (!empty($inactiveListings)): ?>
                    <ul>
                        <?php foreach ($inactiveListings as $listing): ?>
                            <li><?= Html::encode($listing->card->name) ?> - <?= number_format($listing->price, 2) ?>€</li>
                        <?php endforeach; ?>
                    </ul>
                <?php else: ?>
                    <p>No inactive listings.</p>
                <?php endif; ?>
            </div>
        </div>
    <?php endif; ?>

</div>
