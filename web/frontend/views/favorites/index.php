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
<div class="favorites-index row">
    <div class="col-8">
        <h2><?= Html::encode($this->title) ?></h2>
    </div>
</div>

<?= ListView::widget([
    'dataProvider' => $dataProvider,
    'itemOptions' => ['class' => 'item col-md-4'],
    'itemView' => '@frontend/views/card/_card',
    'layout' => "<div class='row g-3'>{items}</div>\n{pager}"
]) ?>
