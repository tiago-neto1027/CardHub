<?php

use yii\helpers\Html;
use yii\jui\DatePicker;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var common\models\Punishment $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="punishment-form">

    <?php $form = ActiveForm::begin(); ?>

    <label class="control-label">User</label>
    <p class="form-control"><?=$model->user->username?></p>

    <label class="control-label">Admin</label>
    <p class="form-control"><?=$model->admin->username?></p>

    <?= $form->field($model, 'user_id')->hiddenInput([
        'value' => $model->user_id,
    ])->label(false) ?>

    <?= $form->field($model, 'admin_id')->hiddenInput([
        'value' => $model->admin_id,
    ])->label(false) ?>

    <?= $form->field($model, 'reason')->textInput(['maxlength' => true])->label('Reason') ?>

    <?= $form->field($model, 'start_date')->widget(DatePicker::classname(), [
        'options' => ['placeholder' => 'Select start date: '],
        'dateFormat' => 'yyyy-MM-dd',
        'clientOptions' => [
            'changeMonth' => true,
            'changeYear' => true,
            'yearRange' => '1900:2099',
        ],
    ])->label('Select start date: ') ?>

    <?= $form->field($model, 'end_date')->widget(DatePicker::classname(), [
        'options' => ['placeholder' => 'Select end date: '],
        'dateFormat' => 'yyyy-MM-dd',
        'clientOptions' => [
            'changeMonth' => true,
            'changeYear' => true,
            'yearRange' => '1900:2099',
        ],
    ])->label('Select end date: ') ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
