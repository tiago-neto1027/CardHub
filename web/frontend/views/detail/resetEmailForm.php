<?php

/** @var yii\web\View $this */
/** @var yii\bootstrap5\ActiveForm $form */
/** @var \common\models\User $model */

use yii\bootstrap5\Html;
use yii\bootstrap5\ActiveForm;

$this->title = 'Reset Email';
?>
<div class="col-6" style="margin-left: 50px">
    <h1><?= Html::encode($this->title) ?></h1>
    <?php $form = ActiveForm::begin(['id' => 'reset-email', 'method' => 'post']); ?>
    <div style="margin-top:100px">
        <h5>Current email:  <span style="font-size: 16px; font-weight: normal;"></h5>
        <?= $form->field($user, 'email')->textInput(['readonly' => true])->label(false) ?>    </div>
    <div style="margin-top:80px">
        <h5>New Email: </h5>
        <?= $form->field($user, 'email')->textInput(['value' => ''])->label(false) ?>
    </div>

    <div class="form-group">
        <?= Html::submitButton('ChangeEmail', ['class' => 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>


</div>