<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\Card $model */

$this->title = 'Suggest a new card';
$this->params['breadcrumbs'][] = ['label' => 'Cards', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="container my-5">
    <div class="card shadow-lg border-0">
        <div class="card-header bg-primary text-white">
            <h3 class="mb-0"><i class="fas fa-plus-circle me-2"></i><?= Html::encode($this->title) ?></h3>
        </div>
        <div class="card-body">
            <?= $this->render('_form', [
                'model' => $model,
            ]) ?>
        </div>
    </div>
</div>
