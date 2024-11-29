<?php

use common\models\Listing;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\widgets\ListView;

/** @var yii\web\View $this */
/** @var common\models\ListingSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Listings';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="listing-index row">

    <div class="col-8">
        <h1><?= Html::encode($this->title) ?></h1>
    </div>

    <div class="col-4 text-end align-content-end">
        <p>
            <?= Html::a('Create Listing', ['create'], ['class' => 'btn btn-success']) ?>
        </p>
    </div>
</div>

<?= ListView::widget([
    'dataProvider' => $dataProvider,
    'itemOptions' => ['class' => 'item col-md-6'],
    'itemView' => '_listing',
    'layout' => "<div class='row g-3'>{items}</div>\n{pager}"
]) ?>