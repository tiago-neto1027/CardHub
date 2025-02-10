<?php

use common\models\Listing;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\widgets\ListView;

/** @var yii\web\View $this */
/** @var common\models\ListingSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'My favorites';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="container my-5">
    <div class="card shadow-lg border-0">
        <div class="card-header bg-primary text-white">
            <h3 class="mb-0"><i class="fas fa-heart me-2"></i><?= Html::encode($this->title) ?></h3>
        </div>
        <div class="card-body">
            <?= ListView::widget([
                'dataProvider' => $dataProvider,
                'itemOptions' => ['class' => 'item col-lg-3 col-md-4 col-sm-12 mb-4'],
                'itemView' => '@frontend/views/card/_card',
                'layout' => "<div class='row g-3'>{items}</div>\n{pager}"
            ]) ?>
        </div>
    </div>
</div>
