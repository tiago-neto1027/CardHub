<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var common\models\User $model */

$this->title = $model->username;
$this->params['breadcrumbs'][] = ['label' => 'Users', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="user-view">

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?php
            if($model->status != 0){
                echo Html::a('Delete', ['delete', 'id' => $model->id], [
                    'class' => 'btn btn-danger',
                    'data' => [
                        'confirm' => 'Are you sure you want to delete this item?',
                        'method' => 'post',
                    ],
                ]);
            }
        ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            //'id',
            'username',
            //'auth_key',
            //'password_hash',
            //'password_reset_token',
            'email:email',
            [
                'attribute' => 'role',
                'value' => function ($model) {
                    return $model->getRole();
                },
                'label' => 'User Type',
            ],
            [
                'attribute' => 'status',
                'value' => function ($model) {
                    $statuses = [
                        10 => 'Active',
                        9 => 'Inactive',
                        0 => 'Deleted',
                    ];

                    return $statuses[$model->status] ?? 'Unknown';
                },
                'label' => 'Status',
            ],
            [
                'attribute' => 'created_at',
                'format' => ['date', 'php:d/m/Y'],
                'value' => function ($model) {
                    return $model->created_at;
                },
                'label' => 'Created At',
            ],
            [
                'attribute' => 'updated_at',
                'format' => ['date', 'php:d/m/Y'],
                'value' => function ($model) {
                    return $model->updated_at;
                },
                'label' => 'Updated At',
            ],
            //'verification_token',
        ],
    ]) ?>

</div>
