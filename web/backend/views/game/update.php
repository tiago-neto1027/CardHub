<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\Game $model */

$this->title = 'Update Game: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Games', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="game-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
