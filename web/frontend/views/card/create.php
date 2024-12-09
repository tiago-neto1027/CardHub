<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\Card $model */

$this->title = 'Suggest a new card';
$this->params['breadcrumbs'][] = ['label' => 'Cards', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="card-create">

    <h2><?= Html::encode($this->title) ?></h2>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
