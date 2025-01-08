<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\Punishment $model */

$this->title = 'Update Punishment: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Punishments', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="punishment-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
