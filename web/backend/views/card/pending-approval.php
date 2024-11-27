<?php

use common\models\Card;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var common\models\CardSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Cards Pending Approval';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="card-pending-approval">

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'id',
            'name',
            [
                'attribute' => 'game_id',
                'value' => function ($model) {
                    return $model->game->name;
                },
                'label' => 'Game',
            ],
            'rarity',
            /*[
                'attribute' => 'image_url',
                'label' => 'Logo',
                'format' => 'raw',
                'value' => function ($model) {
                    return Html::img($model->image_url, ['alt' => $model->name, 'style' => 'max-width:200px;']);
                },
            ],*/
            //'image_url:url',
            //'description',
            //'status',
            [
                'attribute' => 'created_at',
                'format' => ['date', 'php:d/m/Y'],
                'value' => function ($model) {
                    return $model->created_at;
                },
                'label' => 'Created At',
            ],
            [
                'contentOptions' => ['style' => 'white-space: nowrap;'],
                'class' => ActionColumn::className(),
                'header' => 'Actions',
                'template' => '{view} {accept} {reject} {info}',
                'buttons' => [
                    'view' => function ($url, $model, $key) {
                        return Html::a('View', ['view', 'id' => $model->id], [
                            'class' => 'btn btn-info btn-sm',
                            'data-method' => 'post',
                        ]);
                    },
                    'accept' => function ($url, $model, $key) {
                        return Html::a('Accept', ['accept', 'id' => $model->id], [
                            'class' => 'btn btn-success btn-sm',
                            'data-method' => 'post',
                        ]);
                    },
                    'reject' => function ($url, $model, $key) {
                        return Html::a('Reject', ['delete', 'id' => $model->id], [
                            'class' => 'btn btn-danger btn-sm',
                            'data-method' => 'post',
                        ]);
                    },
                    'info' => function ($url, $model, $key) {
                        return Html::a('User Info', ['user-info', 'id' => $model->id], [
                            'class' => 'btn btn-primary btn-sm',
                        ]);
                    },
                ],
                'urlCreator' => function ($action, Card $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id' => $model->id]);
                }
            ],
        ],
    ]); ?>


</div>
