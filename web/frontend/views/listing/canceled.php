<?php

use common\models\Listing;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\widgets\ListView;

/** @var yii\web\View $this */
/** @var common\models\ListingSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Canceled listings';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="listing-index row">
    <div class="col-10 mb-5">
        <h2><?= Html::encode($this->title) ?></h2>
    </div>
    <div class="col-10">
        <?= ListView::widget([
            'dataProvider' => $dataProvider,
            'itemOptions' => ['class' => 'item col-lg-2 col-md-4 col-sm-6 pb-1'],
            'itemView' => function ($model, $key, $index, $widget) use ($isActiveView) {
                return $this->render('_listing', [
                    'model' => $model,
                    'isActiveView' => $isActiveView,
                ]);
            },
            'layout' => "<div class='row g-3'>{items}</div>\n{pager}",
        ]) ?>
    </div>

    <div class="col-2 text-end d-flex flex-column">
        <p class="mb-5">
            <?= Html::a('Sell other product', ['create'], ['class' => 'btn bg-primary text-dark w-75']) ?>
        </p>
        <p class="mb-5">
            <?= Html::a('Sold items', ['sold'], ['class' => 'btn bg-primary text-dark w-75']) ?>
        </p>
        <p class="mb-5">
            <?= Html::a('Deleted Items', ['canceled'], ['class' => 'btn bg-primary text-dark w-75']) ?>
        </p>
    </div>
</div>
