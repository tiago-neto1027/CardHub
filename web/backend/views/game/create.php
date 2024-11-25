<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\Game $model */

$this->title = 'Create Game';
$this->params['breadcrumbs'][] = ['label' => 'Games', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="game-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
