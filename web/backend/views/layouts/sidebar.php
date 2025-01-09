<?php

use common\models\Card;
use common\models\Product;

?>
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="<?= \yii\helpers\Url::to(['site/index']) ?>" class="brand-link">
        <img src="<?= $assetDir ?>/img/AdminLTELogo.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3"
             style="opacity: .8">
        <span class="brand-text font-weight-light">AdminLTE 3</span>
    </a>


    <!-- Sidebar -->
    <div class="sidebar">
        <!--User-->
        <div class="user-panel my-2 pb-2 pl-2 ml-0 d-flex">
            <div class="info">
                <a href="#" class="d-block"><?= Yii::$app->user->identity->username ?? 'Guest' ?></a>
            </div>
        </div>

        <!-- SidebarSearch Form -->
        <!-- href be escaped -->
        <!-- <div class="form-inline">
            <div class="input-group" data-widget="sidebar-search">
                <input class="form-control form-control-sidebar" type="search" placeholder="Search" aria-label="Search">
                <div class="input-group-append">
                    <button class="btn btn-sidebar">
                        <i class="fas fa-search fa-fw"></i>
                    </button>
                </div>
            </div>
        </div> -->

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <?php
            $pendingCount = Card::getPendingCardCount();
            $lowStockCount = Product::getLowStockCount();

            echo \hail812\adminlte\widgets\Menu::widget([
                'items' => [
                    /*[
                        'label' => 'Starter Pages',
                        'icon' => 'tachometer-alt',
                        'badge' => '<span class="right badge badge-info">2</span>',
                        'items' => [
                            ['label' => 'Active Page', 'url' => ['site/index'], 'iconStyle' => 'far'],
                            ['label' => 'User Management', 'url' =>['user/index'], 'iconStyle' => 'far'],
                            ['label' => 'Inactive Page', 'iconStyle' => 'far'],
                        ]
                    ],*/
                    ['label' => 'Dashboard', 'url' => ['site/index'], 'icon' => 'tachometer-alt'],

                    //ACTIVITY MENUS
                    ['label' => 'ACTIVITIES', 'header' => true],
                    ['label' => 'Pending Cards',
                        'url' => ['card/pending-approval'],
                        'icon' => 'bell',
                        'badge' => '<span class="right badge badge-danger">' . $pendingCount . '</span>',
                    ],
                    ['label' => 'Low Stock Products',
                        'url' => ['product/low-stock'],
                        'icon' => 'box',
                        'badge' => '<span class="right badge badge-danger">' . $lowStockCount . '</span>',
                    ],

                    //CRUD MENUS
                    ['label' => 'CRUD MENUS', 'header' => true],
                    ['label' => 'User Management', 'url' => ['user/index'], 'icon' => 'user'],
                    ['label' => 'Game Management', 'url' => ['game/index'], 'icon' => 'gamepad'],
                    ['label' => 'Card Management', 'url' => ['card/index'], 'icon' => 'dragon'],
                    ['label' => 'Product Management', 'url' => ['product/index'], 'icon' => 'shopping-cart'],
                    ['label' => 'User Punishments', 'url' => ['punishment/index'], 'icon' => 'gavel'],

                    //REVENUE MENUS
                    ['label' => 'REVENUES', 'header' => true,],
                    ['label' => 'Product Transactions', 'url' => ['transaction/products'], 'icon' => 'money-bill-wave'],
                    ['label' => 'Card Transactions', 'url' => ['transaction/cards'], 'icon' => 'history'],
                    ['label' => 'Seller Revenues', 'url' => ['user/sellers'], 'icon' => 'chart-line'],

                ],
            ]);
            ?>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>