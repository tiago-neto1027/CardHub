<?php

/** @var yii\web\View $this */
/** @var yii\bootstrap5\ActiveForm $form */
/** @var \common\models\User $model */

use yii\bootstrap5\Html;
use yii\bootstrap5\ActiveForm;

$this->title = 'Change Email';
?>
<div class="col-6 ml-4">
    <h1><?= Html::encode($this->title) ?></h1>
    <?php $form = ActiveForm::begin([
        'id' => 'change-email-form',
        'method' => 'post',
        'action' => ['detail/change-email', 'id' => $user->id]
    ]); ?>
    <div class="mt-5">
        <h5>Current email:  </h5>
        <?= $form->field($user, 'email')->textInput(['readonly' => true])->label(false) ?>
    </div>
    <div class="mt-5">
        <h5>New Email: </h5>
        <?= Html::input('text', 'newEmail', '', ['class' => 'form-control']) ?>
    </div>

    <div class="mt-5">
        <?= Html::submitButton('Change Email', ['class' => 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>



