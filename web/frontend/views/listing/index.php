<?php

use common\models\Listing;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\widgets\ListView;

/** @var yii\web\View $this */
/** @var common\models\ListingSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'My listings';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="listing-index row">
    <div class="col-8">
        <h2><?= Html::encode($this->title) ?></h2>
    </div>
    <div class="col-4 text-end align-content-end">
        <p>
            <?= Html::a('Sell other product', ['create'], ['class' => 'btn btn-success']) ?>
        </p>
    </div>
</div>

<?= ListView::widget([
    'dataProvider' => $dataProvider,
    'itemOptions' => ['class' => 'item col-md-4'],
    'itemView' => '_listing',
    'layout' => "<div class='row g-3'>{items}</div>\n{pager}"
]) ?>