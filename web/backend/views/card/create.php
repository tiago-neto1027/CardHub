<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\Card $model */

$this->title = 'Create Card';
$this->params['breadcrumbs'][] = ['label' => 'Cards', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="card-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
