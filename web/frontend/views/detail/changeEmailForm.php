<?php

/** @var yii\web\View $this */
/** @var yii\bootstrap5\ActiveForm $form */
/** @var \common\models\User $model */

use yii\bootstrap5\Html;
use yii\bootstrap5\ActiveForm;

$this->title = 'Change Email';
?>
<div class="card shadow-lg border-0 mb-5">
    <div class="card-header bg-primary text-white">
        <h3 class="mb-0"><i class="fas fa-envelope me-2"></i><?= Html::encode($this->title) ?></h3>
    </div>
    <div class="card-body">
        <?php $form = ActiveForm::begin([
            'id' => 'change-email-form',
            'method' => 'post',
            'action' => ['detail/change-email', 'id' => $user->id],
            'options' => ['class' => 'needs-validation', 'novalidate' => true]
        ]); ?>

        <!-- Current Email Field -->
        <div class="mb-4">
            <label class="form-label text-muted"><i class="fas fa-envelope me-2"></i>Current Email</label>
            <?= $form->field($user, 'email')->textInput([
                'class' => 'form-control form-control-lg',
                'readonly' => true
            ])->label(false) ?>
        </div>

        <!-- New Email Field -->
        <div class="mb-4">
            <label for="newEmail" class="form-label text-muted"><i class="fas fa-envelope-open me-2"></i>New Email</label>
            <?= Html::input('text', 'newEmail', '', [
                'class' => 'form-control form-control-lg',
                'required' => true,
                'placeholder' => 'Enter new email'
            ]) ?>
            <div class="invalid-feedback">Please enter a valid email address.</div>
        </div>

        <!-- Submit Button -->
        <div class="text-center">
            <?= Html::submitButton('<i class="fas fa-save me-2"></i>Change Email', [
                'class' => 'btn btn-outline-primary btn-lg rounded-pill px-4'
            ]) ?>
        </div>

        <?php ActiveForm::end(); ?>
    </div>
</div>
