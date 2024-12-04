<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\jui\AutoComplete;

/** @var yii\web\View $this */
/** @var common\models\Listing $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="listing-form">

    <?php $form = ActiveForm::begin(); ?>

    <label class="control-label" for="listing-price">Card Name</label>
    <?= \yii\jui\AutoComplete::widget([
        'options' => ['class' => 'form-control', 'placeholder' => 'Type card name...'],
        'clientOptions' => [
            'source' => \yii\helpers\Url::to(['listing/card-list']),
            'minLength' => 2,
            'select' => new \yii\web\JsExpression('function(event, ui) {
                $("#listing-card_id").val(ui.item.id);
            }'),
        ],
    ]);
    echo $form->field($model, 'card_id')->hiddenInput()->label(false) ?>

    <div class="row">
        <div class="col-6">
            <?= $form->field($model, 'price')->textInput(['placeholder' => 'Type the price...']) ?>
        </div>
        <div class="col-6">
            <?= $form->field($model, 'condition')->dropDownList(
                [
                    'Brand new' => 'Brand new',
                    'Very good' => 'Very good',
                    'Good' => 'Good',
                    'Played' => 'Played',
                    'Poor' => 'Poor',
                    'Damaged' => 'Damaged',
                ],
                ['prompt' => 'Select a Quality']
            ) ?>
        </div>
    </div>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
