<?php

/** @var yii\web\View $this */
/** @var yii\bootstrap5\ActiveForm $form */
/** @var \common\models\User $model */

use yii\bootstrap5\Html;
use yii\bootstrap5\ActiveForm;

$this->title = 'Change Username  ';
?>

<div class="card shadow-lg border-0 mb-5">
    <div class="card-header bg-primary text-white">
        <h3 class="mb-0"><i class="fas fa-user-tag me-2"></i><?= Html::encode($this->title) ?></h3>
    </div>
    <div class="card-body">
        <?php $form = ActiveForm::begin([
            'id' => 'change-username-form',
            'method' => 'post',
            'action' => ['detail/change-username', 'id' => $user->id],
            'options' => ['class' => 'needs-validation', 'novalidate' => true]
        ]); ?>

        <!-- Current Username Field -->
        <div class="mb-4">
            <label class="form-label text-muted"><i class="fas fa-user me-2"></i>Current Username</label>
            <?= $form->field($user, 'username')->textInput([
                'class' => 'form-control form-control-lg',
                'readonly' => true
            ])->label(false) ?>
        </div>

        <!-- New Username Field -->
        <div class="mb-4">
            <label for="newUsername" class="form-label text-muted"><i class="fas fa-user-plus me-2"></i>New Username</label>
            <?= Html::input('text', 'newUsername', '', [
                'class' => 'form-control form-control-lg',
                'required' => true,
                'placeholder' => 'Enter new username'
            ]) ?>
            <div class="invalid-feedback">Please enter a new username.</div>
        </div>

        <!-- Submit Button -->
        <div class="text-center">
            <?= Html::submitButton('<i class="fas fa-save me-2"></i>Change Username', [
                'class' => 'btn btn-outline-primary btn-lg rounded-pill px-4'
            ]) ?>
        </div>

        <?php ActiveForm::end(); ?>
    </div>
</div>



