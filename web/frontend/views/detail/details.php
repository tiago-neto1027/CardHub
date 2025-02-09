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
<h1 class="text-center mb-5 text-light">My Account</h1>
<div class="container">
    <div class="card shadow-lg border-0">
        <div class="card-header bg-primary text-white">
            <h3 class="mb-0"><i class="fas fa-user-circle me-2"></i>Account Details</h3>
        </div>

        <div class="card-body">
            <!-- User Name Section -->
            <div class="row align-items-center mb-4">
                <div class="col-md-8">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-user-tag text-primary fs-5 me-3"></i>
                        <div>
                            <h5 class="mb-0 text-muted">User Name</h5>
                            <p class="lead mb-0"><?= $user->username ?></p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 text-end">
                    <?= Html::a('<i class="fas fa-pencil-alt me-2"></i>Change Username',
                        ['/detail/change-username-form', 'id' => Yii::$app->user->id], [
                            'class' => 'btn btn-outline-primary btn-lg rounded-pill px-4',
                            'role' => 'button'
                        ]) ?>
                </div>
            </div>

            <hr class="my-4">

            <!-- Email Section -->
            <div class="row align-items-center mb-4">
                <div class="col-md-8">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-envelope text-primary fs-5 me-3"></i>
                        <div>
                            <h5 class="mb-0 text-muted">Email</h5>
                            <p class="lead mb-0"><?= $user->email ?></p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 text-end">
                    <?= Html::a('<i class="fas fa-pencil-alt me-2"></i>Change Email',
                        ['/detail/change-email-form', 'id' => Yii::$app->user->id], [
                            'class' => 'btn btn-outline-primary btn-lg rounded-pill px-4',
                            'role' => 'button'
                        ]) ?>
                </div>
            </div>

            <hr class="my-4">

            <!-- Password Section -->
            <div class="d-flex justify-content-end">
                <?= Html::a('<i class="fas fa-lock me-2"></i>Change Password',
                    ['/detail/change-password-form', 'id' => Yii::$app->user->id], [
                        'class' => 'btn btn-outline-primary btn-lg rounded-pill px-4',
                        'role' => 'button'
                    ]) ?>
            </div>
        </div>
    </div>

    <!-- My Orders Section -->
    <div class="mt-5 mb-4">
        <h2 class="text-center mb-4 text-light">My Orders</h2>
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
                                        'class' => 'btn btn-info btn-sm rounded text-light',
                                        'role' => 'button'
                                    ]) ?>
                                    <?php if ($invoice->status === 'Pending'): ?>
                                        <?= Html::a('Pay', ['/payment/view', 'source' => 'detail','id' => $invoice->id], [
                                            'class' => 'btn btn-success btn-sm rounded mx-1 text-light',
                                            'role' => 'button'
                                        ]) ?>
                                        <?= Html::a('Cancel', ['/invoice/cancel', 'id' => $invoice->id], [
                                            'class' => 'btn btn-danger btn-sm rounded text-light',
                                            'role' => 'button',
                                            'data-confirm' => 'Are you sure you want to cancel this order?',
                                            'data-method' => 'post'
                                        ]) ?>
                                    <?php elseif($invoice->status === 'Completed'): ?>
                                        <?= Html::a('Download PDF', ['/invoice/download-pdf', 'id' => $invoice->id], [
                                            'class' => 'btn btn-success btn-sm rounded text-light',
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
