<?php

use common\models\Card;

?>
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="index3.html" class="brand-link">
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

                    //CRUD MENUS
                    ['label' => 'CRUD MENUS', 'header' => true],
                    ['label' => 'User Management', 'url' => ['user/index'], 'icon' => 'user'],
                    ['label' => 'Game Management', 'url' => ['game/index'], 'icon' => 'gamepad'],
                    ['label' => 'Card Management', 'url' => ['card/index'], 'icon' => 'dragon'],
                    ['label' => 'Product Management', 'url' => ['product/index'], 'icon' => 'shopping-cart'],

                    //REVENUE MENUS
                    ['label' => 'REVENUES', 'header' => true],

                    ['label' => 'OTHERS', 'header' => true],
                    [
                        'label' => 'TODO',
                        'icon' => 'exclamation',
                        'items' => [
                            ['label' => 'Reviews', 'iconStyle' => 'far'],
                            ['label' => 'Statistics', 'iconStyle' => 'far'],
                            ['label' => 'Card Sales', 'iconStyle' => 'far'],
                            ['label' => 'Product Sales', 'iconStyle' => 'far'],
                        ]
                    ],

                    /*['label' => 'Simple Link', 'icon' => 'th', 'badge' => '<span class="right badge badge-danger">New</span>'],
                    ['label' => 'Yii2 PROVIDED', 'header' => true],
                    ['label' => 'Login', 'url' => ['site/login'], 'icon' => 'sign-in-alt', 'visible' => Yii::$app->user->isGuest],*/
                    ['label' => 'Gii', 'icon' => 'file-code', 'url' => ['/gii'], 'target' => '_blank'],
                    /*['label' => 'Debug', 'icon' => 'bug', 'url' => ['/debug'], 'target' => '_blank'],*/
                    /*['label' => 'MULTI LEVEL EXAMPLE', 'header' => true],
                    ['label' => 'Level1'],
                    [
                        'label' => 'Level1',
                        'items' => [
                            ['label' => 'Level2', 'iconStyle' => 'far'],
                            [
                                'label' => 'Level2',
                                'iconStyle' => 'far',
                                'items' => [
                                    ['label' => 'Level3', 'iconStyle' => 'far', 'icon' => 'dot-circle'],
                                    ['label' => 'Level3', 'iconStyle' => 'far', 'icon' => 'dot-circle'],
                                    ['label' => 'Level3', 'iconStyle' => 'far', 'icon' => 'dot-circle']
                                ]
                            ],
                            ['label' => 'Level2', 'iconStyle' => 'far']
                        ]
                    ],
                    ['label' => 'Level1'],/*
                    /*['label' => 'LABELS', 'header' => true],
                    ['label' => 'Important', 'iconStyle' => 'far', 'iconClassAdded' => 'text-danger'],
                    ['label' => 'Warning', 'iconClass' => 'nav-icon far fa-circle text-warning'],
                    ['label' => 'Informational', 'iconStyle' => 'far', 'iconClassAdded' => 'text-info'],*/
                ],
            ]);
            ?>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>