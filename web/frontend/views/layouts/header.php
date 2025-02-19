<?php

use yii\helpers\Html;
use yii\helpers\Url;

?>
<head>
    <meta charset="utf-8">
    <title>CardHub - Online Card Hub</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="Free HTML Templates" name="keywords">
    <meta content="Free HTML Templates" name="description">

    <!-- Favicon -->
    <link href="img/favicon.ico" rel="icon">

    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">

    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">

    <!-- Libraries Stylesheet -->
    <link href="lib/animate/animate.min.css" rel="stylesheet">
    <link href="lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">

    <!-- Customized Bootstrap Stylesheet -->
    <link href="web/frontend/web/css/style.css" rel="stylesheet">

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

</head>

<!-- Topbar Start -->
<div class="container-fluid">
    <div class="row py-1 px-xl-5 my-2">
        <!-- TITLE LARGE -->
        <div class="col-lg-6 d-none d-lg-block">
            <div class="d-inline-flex align-items-center h-100">
                <a href="<?= \yii\helpers\Url::home() ?>" class="text-decoration-none mr-3">
                    <span class="h1 text-uppercase text-primary px-2">Card</span>
                    <span class="h1 text-uppercase text-dark bg-primary px-2 ml-n1 rounded">Hub</span>
                </a>
            </div>
        </div>
        <!-- Log In / Sign Up -->
        <div class="col-lg-6 text-center text-lg-right">
            <div class="d-inline-flex align-items-center">
                <?php
                if (Yii::$app->user->isGuest) {
                    echo Html::tag('div',
                        Html::a('Login', ['/site/login'],
                            ['class' => 'btn bg-primary text-black btn-sm',
                            'data-method' => 'post',
                            'id' => 'login-button',]),
                        ['class' => 'btn d-flex']
                    );
                    echo Html::tag('div',
                        Html::a('Sign Up', ['/site/signup'],
                            ['class' => 'btn bg-primary text-black btn-sm',
                            'id' => 'signup-button',]),
                        ['class' => 'btn d-flex']
                    );
                } else {
                    if(Yii::$app->user->can('buyer') && !(Yii::$app->user->can('seller'))){
                        echo Html::tag('div',
                            Html::a('Become a seller', ['/site/seller-form', 'id' => Yii::$app->user->id], ['class' => 'text-black btn bg-primary btn-sm']),
                            ['class' => 'btn d-flex']
                        );
                    }
                    echo Html::tag('div',
                        Html::a('My Account', ['/detail/details', 'id' => Yii::$app->user->id], ['class' => 'text-black btn bg-primary btn-sm']),
                        ['class' => 'btn d-flex']
                    );
                    echo Html::tag('div',
                        Html::a('Logout', ['/site/logout'], ['data-method' => 'post',
                            'class' => 'btn bg-primary btn-sm text-black',
                            'id' => 'logout-button',]),
                        ['class' => 'btn d-flex']
                    );
                }
                ?>
            </div>
            <!-- Little Icons SMALL -->
            <div class="d-inline-flex align-items-center d-block d-lg-none">
                <?php if (!Yii::$app->user->isGuest && Yii::$app->user->identity->getRole() === 'seller'): ?>
                    <?= Html::a(
                        '<i class="fas fa-book-open text-primary"></i>
                            <span class="badge text-secondary border border-secondary rounded-circle" style="padding-bottom: 2px;">' .
                                 Yii::$app->user->identity->getListings(Yii::$app->user->id) .
                            '</span>',
                            ['/listing/index'],
                            ['class' => 'btn px-0']
                    ) ?>
                <?php endif; ?>

                <?php
                $itemCount = 0;
                if (!Yii::$app->user->isGuest) {
                    $itemCount = Yii::$app->user->identity->getFavoritesItemCount() ?? 0;
                }
                ?>

                <?= Html::a(
                    '<i class="fas fa-heart text-primary"></i>
                    <span class="badge text-secondary border border-secondary rounded-circle" style="padding-bottom: 2px;">' .
                    $itemCount .
                    '</span>',
                    ['/favorite/index'],
                    ['class' => 'btn px-0 ml-2']
                ) ?>




                <?php
                $itemCount = 0;

                if (Yii::$app->user->isGuest) {
                    $cartItems = Yii::$app->session->get('cart', []);
                    $itemCount = array_sum(array_column($cartItems, 'quantity'));
                } else {
                    $itemCount = Yii::$app->user->identity->getCartItemCount();
                }
                ?>

                <?= Html::a(
                    '<i class="fas fa-shopping-cart text-primary"></i>
                                <span class="badge text-secondary border border-secondary rounded-circle" style="padding-bottom: 2px;">' .
                    $itemCount .
                    '</span>',
                    ['/cart/index'],
                    ['class' => 'btn px-0 ml-2']
                ) ?>

            </div>
        </div>
    </div>
</div>
<!-- Topbar End -->


<!-- Navbar Start -->
<div class="container-fluid bg-dark mb-30">
    <div class="row px-xl-5">
        <nav class="navbar navbar-expand-lg bg-dark navbar-dark py-3 py-lg-0 px-0">
            <!-- TITLE SMALL -->
            <a href="<?= \yii\helpers\Url::home() ?>" class="text-decoration-none d-block d-lg-none">
                <span class="h1 text-uppercase text-primary px-2">Card</span>
                <span class="h1 text-uppercase text-dark bg-primary px-2 ml-n1">Hub</span>
            </a>
            <!-- Hamburguer Small -->
            <button type="button" class="navbar-toggler" data-toggle="collapse" data-target="#navbarCollapse">
                <span class="navbar-toggler-icon"></span>
            </button>
            <!-- CONTENT -->
            <div class="collapse navbar-collapse justify-content-between" id="navbarCollapse">
                <div class="navbar-nav mr-auto py-0">
                    <a href="<?= Url::home() ?>" class="nav-item nav-link active">Home</a>
                    <a href="<?= Url::to(['/card/index'])?>" class="nav-item nav-link">Browse Cards</a>
                    <?php if ($games = \common\models\Game::getAllGames()): ?>
                    <div class="nav-item dropdown">
                        <a href="#" class="nav-link dropdown-toggle" id="listings-dropdown" data-toggle="dropdown">Listings <i class="fa fa-angle-down"></i></a>
                        <div class="dropdown-menu bg-dark rounded-0 border-0 m-0">
                            <?php
                            foreach ($games as $game) {
                                echo Html::a($game->name, Url::to(['/catalog/index',
                                    'game' => $game->name, 'type' => 'listing']),
                                    ['class' => 'dropdown-item']
                                );
                            }
                            ?>
                        </div>
                    </div>
                    <div class="nav-item dropdown">
                        <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown">Products <i class="fa fa-angle-down"></i></a>
                        <div class="dropdown-menu bg-dark rounded-0 border-0 m-0">
                            <?php
                            foreach ($games as $game) {
                                echo Html::a($game->name, Url::to(['/catalog/index',
                                    'game' => $game->name, 'type' => 'product']),
                                    ['class' => 'dropdown-item']);
                            }
                            ?>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
                <div class="navbar-nav ml-auto py-0 d-none d-lg-block ">
                    <?php if (!Yii::$app->user->isGuest && Yii::$app->user->identity->getRole() === 'seller'): ?>
                        <?= Html::a(
                            '<i class="fas fa-book-open text-primary"></i>
                            <span class="badge text-secondary border border-secondary rounded-circle" style="padding-bottom: 2px;">' .
                            Yii::$app->user->identity->getListings(Yii::$app->user->id) .
                            '</span>',
                            ['/listing/index'],
                            ['class' => 'btn px-0']
                        ) ?>
                    <?php endif; ?>
                    <?php
                    $itemCount = 0;
                    if (!Yii::$app->user->isGuest) {
                        $itemCount = Yii::$app->user->identity->getFavoritesItemCount() ?? 0;
                    }
                    ?>

                    <?= Html::a(
                        '<i class="fas fa-heart text-primary"></i>
                        <span class="badge text-secondary border border-secondary rounded-circle" style="padding-bottom: 2px;">' .
                        $itemCount .
                        '</span>',
                        ['/favorite/index'],
                        ['class' => 'btn px-0 ml-2']
                    ) ?>

                    <?php
                    $itemCount = 0;

                    if (Yii::$app->user->isGuest) {
                        $cartItems = Yii::$app->session->get('cart', []);
                        $itemCount = array_sum(array_column($cartItems, 'quantity'));
                    } else {
                        $itemCount = Yii::$app->user->identity->getCartItemCount();
                    }
                    ?>

                    <?= Html::a(
                        '<i class="fas fa-shopping-cart text-primary"></i>
                                <span class="badge text-secondary border border-secondary rounded-circle" style="padding-bottom: 2px;">' .
                                    $itemCount .
                                '</span>',
                            ['/cart/index'],
                            ['class' => 'btn px-0 ml-2']
                    ) ?>

                </div>

            </div>
        </nav>
    </div>
</div>
<!-- Navbar End -->

    