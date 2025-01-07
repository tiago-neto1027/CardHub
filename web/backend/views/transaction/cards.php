<?php

use common\models\InvoiceLine;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var common\models\InvoiceLineSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Card Transactions';
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
            [
                'attribute' => 'date',
                'label' => 'Transaction Date',
                'value' => function ($model) {
                    return Yii::$app->formatter->asDate($model->cardTransaction->date, 'php:d-m-Y');
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
            /*[
                'attribute' => 'role',
                'value' => function ($model) {
                    return $model->getRole();
                },
                'label' => 'User Type',
                'filter' => Html::activeDropDownList(
                    $searchModel,
                    'user_type',
                    [
                        'manager' => 'manager',
                        'admin' => 'admin',
                        'seller' => 'seller',
                        'buyer' => 'buyer',
                    ],
                    ['class' => 'form-control', 'prompt' => 'Select Role']
                ),
            ],*/

            [
                'contentOptions' => ['style' => 'white-space: nowrap;'],
                'class' => ActionColumn::className(),
                'header' => 'Actions',
                'template' => '{view}',
                'buttons' => [
                    'view' => function ($url, $model) {
                        $cardId = $model->cardTransaction->listing->card_id;

                        if ($cardId !== null) {
                            return Html::a('View Card', ['card/view', 'id' => $cardId], [
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
