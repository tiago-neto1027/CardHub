<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\Punishment $model */

$this->title = 'Create Punishment';
$this->params['breadcrumbs'][] = ['label' => 'Punishments', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="punishment-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
