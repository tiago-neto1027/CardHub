<?php

use common\models\Punishment;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var common\models\PunishmentSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Punishments';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="punishment-index">

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            [
                'attribute' => 'user_id',
                'value' => function ($model) {
                    return $model->user->username;
                },
                'label' => 'User',
            ],
            [
                'attribute' => 'admin_id',
                'value' => function ($model) {
                    return $model->admin->username;
                },
                'label' => 'Admin',
            ],
            'reason',
            [
                'attribute' => 'start_date',
                'value' => function ($model) {
                    return Yii::$app->formatter->asDate($model->start_date, 'dd/MM/yyyy');
                },
                'label' => 'Start Date',
            ],
            [
                'attribute' => 'end_date',
                'value' => function ($model) {
                    return Yii::$app->formatter->asDate($model->end_date, 'dd/MM/yyyy');
                },
                'label' => 'End Date',
            ],
            //'created_at',
            //'updated_at',
            [
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, Punishment $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id' => $model->id]);
                 }
            ],
        ],
    ]); ?>


</div>
