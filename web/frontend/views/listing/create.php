<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\Listing $model */

$this->title = 'Create Listing';
$this->params['breadcrumbs'][] = ['label' => 'Listings', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="listing-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
