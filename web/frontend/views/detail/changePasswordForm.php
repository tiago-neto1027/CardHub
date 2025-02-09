<?php

/** @var yii\web\View $this */
/** @var yii\bootstrap5\ActiveForm $form */
/** @var \common\models\User $model */

use yii\bootstrap5\Html;
use yii\bootstrap5\ActiveForm;

$this->title = 'Change Password';
?>

<div class="card shadow-lg border-0 mb-5">
    <div class="card-header bg-primary text-white">
        <h3 class="mb-0"><i class="fas fa-lock me-2"></i><?= Html::encode($this->title) ?></h3>
    </div>
    <div class="card-body">
        <?php $form = ActiveForm::begin([
            'id' => 'change-password-form',
            'method' => 'post',
            'action' => ['detail/change-password', 'id' => $user->id],
            'options' => ['class' => 'needs-validation', 'novalidate' => true]
        ]); ?>

        <!-- New Password Field -->
        <div class="mb-4">
            <label for="newPassword" class="form-label text-muted"><i class="fas fa-key me-2"></i>New Password</label>
            <?= Html::input('password', 'newPassword', '', [
                'class' => 'form-control form-control-lg',
                'required' => true,
                'placeholder' => 'Enter new password'
            ]) ?>
            <div class="invalid-feedback">Please enter a new password.</div>
        </div>

        <!-- Confirm New Password Field -->
        <div class="mb-4">
            <label for="confirmPassword" class="form-label text-muted"><i class="fas fa-key me-2"></i>Confirm New Password</label>
            <?= Html::input('password', 'confirmPassword', '', [
                'class' => 'form-control form-control-lg',
                'required' => true,
                'placeholder' => 'Confirm new password'
            ]) ?>
            <div class="invalid-feedback">Please confirm your new password.</div>
        </div>

        <!-- Submit Button -->
        <div class="text-center">
            <?= Html::submitButton('<i class="fas fa-save me-2"></i>Change Password', [
                'class' => 'btn btn-lg btn-outline-primary rounded-pill px-4'
            ]) ?>
        </div>

        <?php ActiveForm::end(); ?>
    </div>
</div>


