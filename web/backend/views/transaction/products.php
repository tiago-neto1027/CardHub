<?php

use common\models\InvoiceLine;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var common\models\InvoiceLineSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Product Transactions';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="invoice-line-index">
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'product_name:ntext',
            'price',
            'quantity',

            [
                'attribute' => 'date',
                'label' => 'Transaction Date',
                'value' => function ($model) {
                    return Yii::$app->formatter->asDate($model->productTransaction->date, 'php:d-m-Y');
                },
                'format' => 'raw',
                'filter' => \yii\jui\DatePicker::widget([
                        'model' => $searchModel,
                        'attribute' => 'start_date',
                        'dateFormat' => 'php:Y-m-d',
                        'options' => ['class' => 'form-control', 'placeholder' => 'Start Date'],
                    ]) . ' - ' . \yii\jui\DatePicker::widget([
                        'model' => $searchModel,
                        'attribute' => 'end_date',
                        'dateFormat' => 'php:Y-m-d',
                        'options' => ['class' => 'form-control', 'placeholder' => 'End Date'],
                    ]),
            ],

            [
                'contentOptions' => ['style' => 'white-space: nowrap;'],
                'class' => ActionColumn::className(),
                'header' => 'Actions',
                'template' => '{view}',
                'buttons' => [
                    'view' => function ($url, $model) {
                        $productId = $model->productTransaction->product_id;

                        if ($productId !== null) {
                            return Html::a('View Product', ['product/view', 'id' => $productId], [
                                'class' => 'btn btn-info btn-sm',
                                'data-method' => 'post',
                            ]);
                        }
                        return null;
                    },
                ],
            ],
        ],
    ]); ?>
</div>
