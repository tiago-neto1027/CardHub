<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var common\models\Product $model */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Products', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="product-view">

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            [
                'attribute' => 'game_id',
                'value' => function ($model) {
                    return $model->game->name;
                },
                'label' => 'Game',
            ],
            'name',
            'price',
            'stock',
            'status',
            'image_url:url',
            [
                'attribute' => 'image_url',
                'label' => 'Image',
                'format' => 'raw',
                'value' => function ($model) {
                    return Html::img($model->image_url, ['alt' => $model->name, 'style' => 'max-width:200px;']);
                },
            ],
            'type',
            'description',
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

</div>
