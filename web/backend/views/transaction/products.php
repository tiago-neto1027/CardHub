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

$totalTransactions = $dataProvider->getCount();
$totalMoneyMoved = 0;
$totalQuantitySold = 0;

foreach ($dataProvider->getModels() as $model) {
    $totalMoneyMoved += $model->price;
    $totalQuantitySold += $model->quantity;
}
?>
<div class="invoice-line-index">
    <!-- Summary -->
    <div class="summary-section" style="margin-bottom: 20px;">
        <h3 class="text-primary">Summary</h3>
        <p><strong>Total transactions:</strong> <?= $totalTransactions ?></p>
        <p><strong>Total money moved:</strong> <?= Yii::$app->formatter->asCurrency($totalMoneyMoved) ?></p>
        <p><strong>Total products sold:</strong> <?= $totalQuantitySold ?></p>
    </div>
    <!-- Transactions -->
    <h3 class="text-primary">Transactions</h3>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'product_name:ntext',
            'price',
            'quantity',
            [
                'label' => 'Buyer',
                'value' => function ($model) {
                    return $model->buyer->username;
                },
            ],
            [
                'attribute' => 'status',
                'label' => 'Status',
                'value' => function ($model) {
                    $status = $model->productTransaction->status;
                    return $status === 'active' ? 'Pending' : ($status === 'inactive' ? 'Completed' : $status);
                },
                'filter' => Html::activeDropDownList(
                    $searchModel,
                    'status_filter',
                    [
                        'active' => 'Pending',
                        'inactive' => 'Completed',
                    ],
                    ['class' => 'form-control', 'prompt' => 'Select Status']
                ),
            ],
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
