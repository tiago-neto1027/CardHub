<?php

/** @var yii\web\View $this */
/** @var yii\bootstrap5\ActiveForm $form */
/** @var \common\models\User $model */

use yii\bootstrap5\Html;
use yii\bootstrap5\ActiveForm;

$this->title = 'Change Password';
?>
<div class="col-6 ml-4">
    <h1><?= Html::encode($this->title) ?></h1>
    <?php $form = ActiveForm::begin([
        'id' => 'change-email-form',
        'method' => 'post',
        'action' => ['detail/change-password', 'id' => $user->id]
    ]); ?>
    <div class="mt-5">
        <h5>New password:  </h5>
        <?= Html::input('password', 'newPassword', '', ['class' => 'form-control']) ?>
    </div>
    <div class="mt-5">
        <h5>Confirm new password: </h5>
        <?= Html::input('password', 'confirmPassword', '', ['class' => 'form-control']) ?>
    </div>

    <div class="mt-5">
        <?= Html::submitButton('Change Password', ['class' => 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>


