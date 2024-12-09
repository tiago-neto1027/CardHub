<?php
/** @var yii\web\View $this */
/** @var yii\bootstrap5\ActiveForm $form */
/** @var \common\models\User $user */


use yii\bootstrap5\Html;
use yii\bootstrap5\ActiveForm;

$this->title = 'MyAccount';

?>
<h1 class="text-center mb-5">My Account</h1>
<div class="container">
    <div class="ml-5 rounded bg-body-secondary">
        <div class="row justify-content-between ml-5">
            <div class="col mt-5">
                <p class="font-weight-bold fs-4">User Name:
                    <span class="font-weight-normal fs-5"><?= $user->username ?></span>
                </p>
            </div>
            <div class="col mt-5">
                <?= Html::a('Change Username', ['/detail/change-username-form', 'id' => Yii::$app->user->id], [
                    'class' => 'btn btn-primary btn-sm rounded',
                    'role' => 'button'
                ]) ?>
            </div>
        </div>
        <div class="row justify-content-between ml-5 mt-5 mb-5">
            <div class="col">
                <p class="font-weight-bold fs-4">Email:
                    <span class="font-weight-normal fs-5"><?= $user->email ?></span></p>
            </div>
            <div class="col">
                <?= Html::a('Change Email', ['/detail/change-email-form', 'id' => Yii::$app->user->id], [
                    'class' => 'btn btn-primary btn-sm rounded',
                    'role' => 'button'
                ]) ?>
            </div>
        </div>
        <div class="row justify-content-between ml-5 mt-5 mb-5">
            <div class="col">
                <div class="mt-2 mb-4">
                    <?= Html::a('Change Password', ['/detail/change-password-form', 'id' => Yii::$app->user->id], [
                        'class' => 'btn btn-primary btn-sm rounded',
                        'role' => 'button'
                    ]) ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php

