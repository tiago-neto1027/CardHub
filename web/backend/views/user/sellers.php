<?php

use common\models\User;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var common\models\UserSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Seller Revenues';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-sellers">

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'options' => ['class' => 'col-12'],
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'username',
            [
                'attribute' => 'listings',
                'value' => function ($model) {
                    return $model->getListingsCount();
                },
                'label' => 'Listings',
            ],
            [
                'attribute' => 'sold_listings',
                'value' => function ($model) {
                    return $model->getSoldListingsCount();
                },
                'label' => 'Sold Listings',
            ],
            [
                'attribute' => 'revenue',
                'value' => function ($model) {
                    return $model->getRevenue() . '€';
                },
                'label' => 'Revenue',
            ],
            [
                'class' => ActionColumn::className(),
                'template' => \Yii::$app->user->can('admin') ?
                    '{view} {update} {delete}'
                    : '{view} {delete}',
                'urlCreator' => function ($action, User $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id' => $model->id]);
                }
            ],
        ],
    ]); ?>
</div>
