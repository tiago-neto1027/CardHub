<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\jui\AutoComplete;

/** @var yii\web\View $this */
/** @var common\models\Listing $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="listing-form">
    <?php $form = ActiveForm::begin([
        'id' => 'listing-form',
        'options' => ['class' => 'needs-validation', 'novalidate' => true]
    ]); ?>

    <!-- Card Name Autocomplete -->
    <div class="mb-4">
        <label class="form-label text-muted"><i class="fas fa-search me-2"></i>Card Name</label>
        <?= AutoComplete::widget([
            'options' => [
                'class' => 'form-control form-control-lg',
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
        ]); ?>
        <?= $form->field($model, 'card_id')->hiddenInput(['id' => 'listing-card_id'])->label(false) ?>
    </div>

    <!-- Price and Condition Fields -->
    <div class="row mb-4">
        <div class="col-md-6">
            <?= $form->field($model, 'price', [
                'inputOptions' => [
                    'class' => 'form-control form-control-lg',
                    'placeholder' => 'Type the price...'
                ]
            ])->textInput() ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'condition', [
                'inputOptions' => [
                    'class' => 'form-select form-select-lg',
                ]
            ])->dropDownList(
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

    <!-- Form Actions -->
    <div class="form-group d-flex gap-3">
        <?= Html::submitButton('<i class="fas fa-save me-2 text-black"></i>Save', [
            'class' => 'btn btn-success btn-lg rounded-pill px-4 text-black'
        ]) ?>
        <?= Html::a('<i class="fas fa-plus-circle me-2 text-black"></i>Create New Card', ['/card/create'], [
            'class' => 'btn btn-primary btn-lg rounded-pill px-4 text-black',
            'id' => 'create_card'
        ]) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>

<!-- JavaScript for Autocomplete Behavior -->
<?php
    $js = <<<JS
    $('#autocomplete-card-name').on('blur', function() {
        var inputVal = $(this).val();
        if (!inputVal) {
            $("#listing-card_id").val('');
        }
    });
    $('#autocomplete-card-name').on('input', function() {
        $("#listing-card_id").val('');
    });
    JS;
    $this->registerJs($js);
?>