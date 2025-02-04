<?php

use common\models\User;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var common\models\UserSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Users';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-index row">

    <p class="col-9">
        <?= Html::a('Deleted Users', ['deleted'], ['class' => 'btn btn-danger']) ?>
    </p>
    <?php if (\Yii::$app->user->can('admin')): ?>
        <p class="col-3 d-flex justify-content-end">
            <?= Html::a('Create User', ['create'], ['class' => 'btn btn-success']) ?>
        </p>
    <?php endif; ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'options' => ['class' => 'col-12'],
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'username',
            'email:email',
            [
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
            [
                'class' => ActionColumn::className(),
                'template' => \Yii::$app->user->can('admin') ?
                    '{view} {update} {delete}'
                    : '{view} {delete}',
                'urlCreator' => function ($action, User $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id' => $model->id]);
                }
            ],
        ],
    ]); ?>
</div>
