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

    <!-- The code below creates a label with auto complete for the cards so that the user can ----
    ---- search trough the cards and select it by typing the name. When the user selects a card --
    ---- it's Id is then passed to an hidden field in the form to be submitted. -->
    <label class="control-label" for="listing-price">Card Name</label>
    <?= \yii\jui\AutoComplete::widget([
        'options' => [
            'class' => 'form-control',
            'placeholder' => 'Type card name...',
            'id' => 'autocomplete-card-name',
        ],
        'clientOptions' => [
            'source' => \yii\helpers\Url::to(['listing/card-list']),
            'minLength' => 2,
            'select' => new \yii\web\JsExpression('function(event, ui) {
            $("#listing-card_id").val(ui.item.id);
        }'),
        ],
    ]);
    ?>
    <?= $form->field($model, 'card_id')->hiddenInput(['id' => 'listing-card_id'])->label(false) ?>

    <?php
    //Blur checks whether if the field is blank after loosing focus, then clears the hidden field
    $js = <<<JS
    $('#autocomplete-card-name').on('blur', function() {
        var inputVal = $(this).val();
        if (!inputVal) {
            $("#listing-card_id").val('');
        }
    });
    //Input clears the hidden field everytime the card name is manually changed
    $('#autocomplete-card-name').on('input', function() {
        $("#listing-card_id").val('');
    });
JS;
    $this->registerJs($js);
    ?>

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
        <?= Html::submitButton('Save', ['class' => 'btn btn-success mr-3']) ?>
        <?= Html::a('Create new Card', ['/card/create'],
            ['class' => 'btn btn-primary mr-3',
                'id' => 'create_card']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
