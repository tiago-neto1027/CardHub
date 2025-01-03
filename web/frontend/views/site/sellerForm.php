<?php
use yii\helpers\Html;
?>

<div class="container">
    <h3>Would you like to become a seller?</h3>

    <?= Html::a('Yes, Become a Seller', ['site/become-seller','id' => $id], [
        'class' => 'btn btn-success',
        'data' => [
            'confirm' => 'Are you sure you want to become a seller?',
            'method' => 'post',
        ],
    ]) ?>
</div>
