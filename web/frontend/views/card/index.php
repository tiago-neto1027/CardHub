<?php

use common\models\Card;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var common\models\CardSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Cards';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="card-index">
    <p>
        <?php if (!Yii::$app->user->isGuest) {
            if (Yii::$app->user->identity->getRole() == 'seller')
                Html::a('Create Card', ['create'], ['class' => 'btn btn-success']);
        } ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'summary' => false,
        'tableOptions' => ['class' => 'table custom-table'],
        'columns' => [
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
            'rarity',
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
                'template' => '{view} {favorite}',
                'buttons' => [
                    'view' => function ($url, $model, $key) {
                        return Html::a('View', ['view', 'id' => $model->id], [
                            'class' => 'btn btn-info btn-sm text-light d-block w-100',
                            'data-method' => 'post',
                        ]);
                    },
                    'favorite' => function ($url, $model, $key) {
                        if (!$model->isFavorited()) {
                            return Html::a('Add',
                                ['/favorite/create', 'id' => $model->id],
                                ['class' => 'btn btn-outline-success btn-sm text-white d-block mt-2 w-100']
                            );
                        } else {
                            return Html::a('Remove',
                                ['/favorite/remove', 'id' => $model->id],
                                ['class' => 'btn btn-outline-danger btn-sm text-white d-block mt-2 w-100']
                            );
                        }
                    },
                ],
                'urlCreator' => function ($action, Card $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id' => $model->id]);
                }
            ],
        ],
    ]); ?>
</div>

<style>
    .custom-table {
        border: 1px solid #ddd !important;
        border-collapse: collapse !important;
        color: white !important;
    }

    .custom-table th,
    .custom-table td {
        background-color: transparent !important;
        border: 1px solid #ddd !important;
        padding: 8px;
        color: white !important;
    }

    .custom-table thead th {
        border-bottom: 2px solid #ddd !important;
        color: white !important;
    }

    .custom-table tbody tr:not(:last-child) td {
        border-bottom: 1px solid #ddd !important;
        color: white !important;
    }
</style>
