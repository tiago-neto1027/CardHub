<?php

use yii\helpers\Html;

?>


<div class="card" style="max-width: 540px;">
    <div class="row g-0">
        <div class="col-md-4"">
            <?= Html::img($model->card->image_url, [
                'alt' => $model->card->name,
                'class' => 'img-fluid rounded-start',
            ]);?>
        </div>
        <div class="col-md-8">
            <div class="card-body">
                <h5 class="card-title"><?= $model->card->name ?></h5>
                <p class="card-text"><?= $model->card->description ?></p>
                <p class="card-text"><small class="text-body-secondary"><?= number_format($model->price, 2) ?> €</small></p>
            </div>
        </div>
    </div>
</div>

<style>
    .card-text {
        max-height: 4.5em;
        overflow: auto;
    }
</style>