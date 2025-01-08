<?php

use common\models\Card;
use common\models\Product;
use yii\helpers\Html;

$pendingCount = Card::getPendingCardCount();
$lowStockCount = Product::getLowStockCount();
$totalNotifications = $pendingCount + $lowStockCount;
?>
<!-- Navbar -->
<nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
        </li>
        <li class="nav-item d-none d-sm-inline-block">
            <a href="<?=\yii\helpers\Url::home()?>" class="nav-link">Home</a>
        </li>
    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
        <!-- Notifications Dropdown Menu -->
        <li class="nav-item dropdown">
            <a class="nav-link" data-toggle="dropdown" href="#">
                <i class="far fa-bell"></i>
                <span class="badge badge-warning navbar-badge"><?=$totalNotifications?></span>
            </a>
            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                <span class="dropdown-header"><?=$totalNotifications?> Notifications</span>
                <div class="dropdown-divider"></div>
                <?= Html::a('<i class="fa fa-bell"></i> '. $pendingCount . ' pending cards',
                    ['/card/pending-approval'], ['class' => 'dropdown-item']) ?>
                <div class="dropdown-divider"></div>
                <?= Html::a('<i class="fa fa-box"></i> '. $lowStockCount . ' low stock products',
                    ['/product/low-stock'], ['class' => 'dropdown-item']) ?>
            </div>
        </li>
        <!-- Notifications Dropdown Menu -->
        <li class="nav-item">
            <?= Html::a('<i class="fas fa-sign-out-alt"></i> Logout', ['/site/logout'], [
                'data-method' => 'post',
                'class' => 'nav-link',
                'id' => 'logout-button',
            ]) ?>        </li>
        <li class="nav-item">
            <a class="nav-link" data-widget="fullscreen" href="#" role="button">
                <i class="fas fa-expand-arrows-alt"></i>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-widget="control-sidebar" data-slide="true" href="#" role="button">
                <i class="fas fa-th-large"></i>
            </a>
        </li>
        <li class="nav-item">
            <a href="javascript:history.go(-1);" class="btn btn-primary mx-2 pl-3 pr-4">
                <i class="fa-solid fa-arrow-right"></i> Back
            </a>
        </li>
    </ul>
</nav>
<!-- /.navbar -->