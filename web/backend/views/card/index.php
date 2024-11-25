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
        <?= Html::a('Create Card', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'id',
            [
                'attribute' => 'game_id',
                'value' => function ($model) {
                    return $model->game->name;
                },
                'label' => 'Game',
            ],
            'name',
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
            'status',
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
                'urlCreator' => function ($action, Card $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id' => $model->id]);
                 }
            ],
        ],
    ]); ?>


</div>
