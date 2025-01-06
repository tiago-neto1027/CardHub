<?php

use common\models\Card;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use common\models\Product;

/** @var yii\web\View $this */
/** @var common\models\CardSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Cards';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="product-index">
    <p>
        <?php
        if (!Yii::$app->user->isGuest) {
            if (Yii::$app->user->identity->getRole() == 'seller')
                Html::a('Create Product', ['create'], ['class' => 'btn btn-success']);
        } ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'summary' => false,
        'tableOptions' => ['class' => 'table table-dark table-bordered'],
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            [
                'attribute' => 'image_url',
                'format' => 'html',
                'value' => function ($model) {
                    return Html::img($model->image_url, ['style' => 'width:100px; height:auto;']);
                },
                'label' => 'Image',
                'filter' => false,
            ],
            'name',
            'type',
            'description',
            [
                'attribute' => 'game_id',
                'value' => function ($model) {
                    return $model->game->name;
                },
                'label' => 'Game',
            ],
            [
                'contentOptions' => ['style' => 'white-space: nowrap;'],
                'class' => ActionColumn::className(),
                'header' => 'Actions',
                'template' => '{view}',
                'buttons' => [
                    'view' => function ($url, $model, $key) {
                        return Html::a('View', ['view', 'id' => $model->id], [
                            'class' => 'btn btn-info btn-sm text-light',
                            'data-method' => 'post',
                        ]);
                    },
                ],
                'urlCreator' => function ($action, Product $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id' => $model->id]);
                }
            ],
        ],
    ]); ?>


</div>
