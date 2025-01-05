<?php
/** @var yii\web\View $this */
/** @var yii\bootstrap5\ActiveForm $form */
/** @var \common\models\User $user */
/** @var \common\models\Invoice[] $invoices */

use yii\bootstrap5\Html;
use yii\bootstrap5\ActiveForm;

$this->title = 'MyAccount';

?>
<!-- My Account section -->
<h1 class="text-center mb-5">My Account</h1>
<div class="container">
    <div class="ml-5 rounded bg-body-secondary">
        <div class="row justify-content-between ml-5">
            <div class="col mt-5">
                <p class="font-weight-bold fs-4">User Name:
                    <span class="font-weight-normal fs-5"><?= $user->username ?></span>
                </p>
            </div>
            <div class="col mt-5">
                <?= Html::a('Change Username', ['/detail/change-username-form', 'id' => Yii::$app->user->id], [
                    'class' => 'btn btn-primary btn-sm rounded',
                    'role' => 'button'
                ]) ?>
            </div>
        </div>
        <div class="row justify-content-between ml-5 mt-5 mb-5">
            <div class="col">
                <p class="font-weight-bold fs-4">Email:
                    <span class="font-weight-normal fs-5"><?= $user->email ?></span></p>
            </div>
            <div class="col">
                <?= Html::a('Change Email', ['/detail/change-email-form', 'id' => Yii::$app->user->id], [
                    'class' => 'btn btn-primary btn-sm rounded',
                    'role' => 'button'
                ]) ?>
            </div>
        </div>
        <div class="row justify-content-between ml-5 mt-5 mb-5">
            <div class="col">
                <div class="mt-2 mb-4">
                    <?= Html::a('Change Password', ['/detail/change-password-form', 'id' => Yii::$app->user->id], [
                        'class' => 'btn btn-primary btn-sm rounded',
                        'role' => 'button'
                    ]) ?>
                </div>
            </div>
        </div>
    </div>

    <!-- My Orders Section -->
    <div class="mt-5">
        <h2 class="text-center mb-4">My Orders</h2>
        <div class="rounded bg-light p-4">
            <?php if (!empty($invoices)): ?>
                <table class="table table-bordered">
                    <thead>
                    <tr>
                        <th class="col-2">Order #</th>
                        <th class="col-2">Date</th>
                        <th class="col-2">Status</th>
                        <th class="col-2">Total Cost</th>
                        <th class=" col-2 text-center">Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($invoices as $invoice): ?>
                    <div>
                        <tr>
                            <td><?= $invoice->id ?></td>
                            <td><?= Yii::$app->formatter->asDate($invoice->date, 'php:Y-m-d') ?></td>
                            <td><?= Html::encode($invoice->status) ?></td>
                            <td><?= Yii::$app->formatter->asCurrency($invoice->payment->total ?? 0, 'EUR') ?></td>
                            <td>
                                <div class="d-flex justify-content-between">
                                    <?= Html::a('View', ['/invoice/view', 'id' => $invoice->id], [
                                        'class' => 'btn btn-info btn-sm rounded',
                                        'role' => 'button'
                                    ]) ?>
                                    <?php if ($invoice->status === 'Pending'): ?>
                                        <?= Html::a('Pay', ['/payment/view', 'id' => $invoice->id], [
                                            'class' => 'btn btn-success btn-sm rounded mx-1',
                                            'role' => 'button'
                                        ]) ?>
                                        <?= Html::a('Cancel', ['/invoice/cancel', 'id' => $invoice->id], [
                                            'class' => 'btn btn-danger btn-sm rounded',
                                            'role' => 'button',
                                            'data-confirm' => 'Are you sure you want to cancel this order?',
                                            'data-method' => 'post'
                                        ]) ?>
                                    <?php elseif($invoice->status === 'Completed'): ?>
                                        <?= Html::a('Download PDF', ['/invoice/download-pdf', 'id' => $invoice->id], [
                                            'class' => 'btn btn-success btn-sm rounded',
                                            'role' => 'button',
                                            'data-method' => 'post',
                                            'data-params' => ['id' => $invoice->id]
                                        ]) ?>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p class="text-center">You have no orders.</p>
            <?php endif; ?>
        </div>
    </div>

</div>
