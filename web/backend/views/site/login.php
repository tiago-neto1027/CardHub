<?php
use yii\helpers\Html;
?>
<div class="position-relative">
    <div class="card col-6 center start-50 translate-middle-x">
        <div class="card-body">
            <div class="row">
                <p class="login-box-msg mb-5 col-11 text-center">Sign in to start your session</p>
            </div>
            <?php $form = \yii\bootstrap4\ActiveForm::begin(['id' => 'login-form']) ?>
            <div class="row">
                <div class="col-2"></div>
                <div class="col-7">
                    <?= $form->field($model,'username', [
                        'options' => ['class' => 'form-group has-feedback'],
                        'inputTemplate' => '{input}<div class="input-group-append"></div>',
                        'template' => '{beginWrapper}{input}{error}{endWrapper}',
                        'wrapperOptions' => ['class' => 'input-group mb-4']
                    ])
                        ->label(false)
                        ->textInput(['placeholder' => $model->getAttributeLabel('username')]) ?>
            </div>
            </div>
            <div class="row">
                <div class="col-2"></div>
                <div class="col-7">
                    <?= $form->field($model, 'password', [
                        'options' => ['class' => 'form-group has-feedback'],
                        'inputTemplate' => '{input}<div class="input-group-append"></div>',
                        'template' => '{beginWrapper}{input}{error}{endWrapper}',
                        'wrapperOptions' => ['class' => 'input-group mb-3']
                    ])
                        ->label(false)
                        ->passwordInput(['placeholder' => $model->getAttributeLabel('password')]) ?>
                </div>
            </div>


            <div class="row mb-3">
                <div class="col-11 mb-2 text-center mt-5">
                    <?= Html::submitButton('Sign In', ['class' => 'btn btn-primary btn-block row', '
                    id' => 'login-button']) ?>
                </div>
                <div class="col-11 text-center mt-3">
                    <?= $form->field($model, 'rememberMe')->checkbox([
                        'template' => '<div class="icheck-primary">{input}{label}</div>',
                        'labelOptions' => [
                            'class' => ''
                        ],
                        'uncheck' => null
                    ]) ?>
                </div>
            </div>

            <?php \yii\bootstrap4\ActiveForm::end(); ?>

            <!--
            <div class="social-auth-links text-center mb-3">
                <p>- OR -</p>
                <a href="#" class="btn btn-block btn-primary">
                    <i class="fab fa-facebook mr-2"></i> Sign in using Facebook
                </a>
                <a href="#" class="btn btn-block btn-danger">
                    <i class="fab fa-google-plus mr-2"></i> Sign in using Google+
                </a>
            </div>
            -->
            <!-- /.social-auth-links -->

        </div>
        <!-- /.login-card-body -->
    </div>
</div>