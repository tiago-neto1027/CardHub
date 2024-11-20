<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var common\models\User $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="user-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'username')->textInput(['autofocus' => true]) ?>
    <?= $form->field($model, 'email') ?>
    <?= $form->field($model, 'password')->passwordInput() ?>
    <?= $form->field($model, 'role')->dropDownList([
            'manager' => 'manager',
            'admin' => 'admin',
            'seller' => 'seller',
            'buyer' => 'buyer',
    ], ['prompt' => 'Select Role']) ?>
    <?php if (Yii::$app->controller->action->id === 'update'): ?>
        <?= $form->field($model, 'status')->dropDownList([
            10 => 'Active',
            9 => 'Inactive',
            0 => 'Deleted',
        ], ['prompt' => 'Select Status', 'value' => $model->status]) ?>
    <?php endif; ?>


    <div class="form-group">
        <?= Html::submitButton(
            Yii::$app->controller->action->id === 'update' ? 'Update User' : 'Register User',
            ['class' => 'btn btn-success']
        ) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
