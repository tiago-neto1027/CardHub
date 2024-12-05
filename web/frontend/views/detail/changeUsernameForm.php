<?php

/** @var yii\web\View $this */
/** @var yii\bootstrap5\ActiveForm $form */
/** @var \common\models\User $model */

use yii\bootstrap5\Html;
use yii\bootstrap5\ActiveForm;

$this->title = 'Change Username  ';
?>
<div class="col-6 ml-4">
    <h1><?= Html::encode($this->title) ?></h1>
    <?php $form = ActiveForm::begin([
        'id' => 'change-username-form',
        'method' => 'post',
        'action' => ['detail/change-username', 'id' => $user->id]
    ]); ?>
    <div class="mt-5">
        <h5>Current username:  </h5>
        <?= $form->field($user, 'username')->textInput(['readonly' => true])->label(false) ?>
    </div>
    <div class="mt-5">
        <h5>New username: </h5>
        <?= Html::input('text', 'newUsername', '', ['class' => 'form-control']) ?>
    </div>

    <div class="mt-5">
        <?= Html::submitButton('Change Username', ['class' => 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>



