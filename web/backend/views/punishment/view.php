<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var common\models\Punishment $model */

$this->title = "Punishment: " . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Punishments', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="punishment-view">

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
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
            'start_date',
            'end_date',
            'created_at',
            'updated_at',
        ],
    ]) ?>

</div>
