<?php
/** @var yii\web\View $this */
/** @var yii\bootstrap5\ActiveForm $form */
/** @var \common\models\User $user */


use yii\bootstrap5\Html;
use yii\bootstrap5\ActiveForm;

?>
<h1>Welcome to My Account</h1>
<p>User ID: <?= $user->id ?></p>
<p>User Name: <?= $user->username ?></p>
<!-- Display any other user info -->
<?php

