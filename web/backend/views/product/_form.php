<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var common\models\Product $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="product-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'game_id')->dropDownList(
        \yii\helpers\ArrayHelper::map(\common\models\Game::find()->all(), 'id', 'name'),
        ['prompt' => 'Select a Game']
    ) ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'type')->dropDownList(
        [
            'booster' => 'Booster',
            'sleeve' => 'Sleeve',
            'playmat' => 'Playmat',
            'storage' => 'Storage',
            'guide' => 'Guide',
            'apparel' => 'Apparel',
        ],
        ['prompt' => 'Select Type']
    ) ?>

    <?= $form->field($model, 'price')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'stock')->textInput() ?>

    <?= $form->field($model, 'status')->dropDownList([ 'active' => 'Active', 'inactive' => 'Inactive', ], ['prompt' => '']) ?>

    <?= $form->field($model, 'description')->textInput(['maxlength' => true]) ?>

    <!--<?= $form->field($model, 'created_at')->textInput() ?>-->

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
