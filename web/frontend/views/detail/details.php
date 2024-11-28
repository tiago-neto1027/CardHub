<?php
/** @var yii\web\View $this */
/** @var yii\bootstrap5\ActiveForm $form */
/** @var \common\models\User $user */


use yii\bootstrap5\Html;
use yii\bootstrap5\ActiveForm;

?>
<h1 style="text-align: center;">My Account</h1>
    <div style="margin-left: 50px;">
        <p style="font-size: 20px; font-weight: bold;">User Name:
            <span style="font-size: 16px; font-weight: normal;"><?= $user->username ?></span></p>
        <p style="font-size: 20px; font-weight: bold;">Email:
            <span style="font-size: 16px; font-weight: normal;"><?= $user->email ?></span></p>
        <p style="margin-bottom: 150px"></p>

        <?= Html::button('Change username', ['class' => 'btn btn-sm btn-dark', 'name' => 'RequestPasswordReset']) ?>
        <?= Html::a('Change email', ['/detail/reset-email-form','id' => Yii::$app->user->id], ['class' => 'btn btn-sm btn-dark']) ?>

        <p style="margin-top: 20px">
            <?= Html::a('Change password', ['/site/request-password-reset '], ['class' => 'btn btn-sm btn-dark']) ?>
        </p>
    </div>
<?php

