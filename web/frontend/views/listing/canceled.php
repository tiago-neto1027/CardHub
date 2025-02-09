<?php
use common\models\Listing;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\widgets\ListView;

/** @var yii\web\View $this */
/** @var common\models\ListingSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Canceled Listings';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="container my-5">
    <div class="card shadow-lg border-0">
        <div class="card-header bg-primary text-white">
            <h3 class="mb-0"><i class="fas fa-trash-alt me-2"></i><?= Html::encode($this->title) ?></h3>
        </div>
        <div class="card-body">
            <div class="row">
                <!-- Listings Grid -->
                <div class="col-md-9">
                    <?= ListView::widget([
                        'dataProvider' => $dataProvider,
                        'itemOptions' => ['class' => 'item col-lg-4 col-md-6 col-sm-12 mb-4'],
                        'itemView' => function ($model, $key, $index, $widget) use ($isActiveView) {
                            return $this->render('_listing', [
                                'model' => $model,
                                'isActiveView' => $isActiveView,
                            ]);
                        },
                        'layout' => "<div class='row g-3'>{items}</div>\n{pager}",
                    ]) ?>
                </div>

                <!-- Sidebar Actions -->
                <div class="col-md-3">
                    <div class="d-flex flex-column gap-3">
                        <?= Html::a('<i class="fas fa-plus-circle me-2"></i>Sell Other Product', ['create'], [
                            'class' => 'btn btn-outline-primary btn-lg rounded-pill w-100'
                        ]) ?>
                        <?= Html::a('<i class="fas fa-book-open me-2"></i>Current Listings', ['index'], [
                            'class' => 'btn btn-outline-primary btn-lg rounded-pill w-100'
                        ]) ?>
                        <?= Html::a('<i class="fas fa-check-circle me-2"></i>Sold Items', ['sold'], [
                            'class' => 'btn btn-outline-primary btn-lg rounded-pill w-100'
                        ]) ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>