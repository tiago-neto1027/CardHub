<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var common\models\Card $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="card-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'game_id')->label('Game')->dropDownList(
        \yii\helpers\ArrayHelper::map(\common\models\Game::find()->all(), 'id', 'name'),
        ['prompt' => 'Select a Game']
    ) ?>

    <?= $form->field($model, 'rarity')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'image_url')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'description')->textarea() ?>

    <?= $form->field($model, 'status')->dropDownList([
            'active' => 'Active',
            'inactive' => 'Inactive', ],
        ['prompt' => 'Select a Status']) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
