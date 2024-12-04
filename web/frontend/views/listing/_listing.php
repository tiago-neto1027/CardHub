<?php

use yii\helpers\Html;

?>


<div class="card" style="max-width: 540px;">
    <div class="row g-0">
        <div class="col-md-4">
            <?= Html::img($model->card->image_url, [
                'alt' => $model->card->name,
                'class' => 'img-fluid rounded-start',
            ]);?>
        </div>
        <div class="col-md-8">
            <div class="card-body">
                <h5 class="card-title"><?= $model->card->name ?></h5>
                <p class="card-text"><?= $model->card->description ?></p>
                <p class="card-text"><strong>Condition: </strong><?= $model->condition ?></p>
                <p class="card-text"><strong>Price: </strong><?= number_format($model->price, 2) ?> €</p>
            </div>
        </div>
    </div>
    <div class="position-absolute top-100 end-0 p-2">
        <?= Html::a(
            '<i class="fas fa-trash-alt" style="font-size: 1.5rem;"></i>',
            ['listing/delete', 'id' => $model->id],
            [
                'class' => 'text-danger',
                'data' => [
                    'confirm' => 'Are you sure you want to delete this item?',
                    'method' => 'post',
                ],
            ]
        ); ?>
    </div>
</div>

<style>
    .card-text {
        max-height: 4.5em;
        overflow: auto;
    }
</style>