<?php

use yii\helpers\Html;
use yii\helpers\Url;

?>

<!-- Footer Start -->
<div class="container-fluid bg-dark text-secondary mt-auto pt-5">
        <div class="row px-xl-5">
            <div class="col-lg-4 col-md-12 mb-5 pr-3 pr-xl-5">
                <h5 class="text-secondary text-uppercase mb-4">CardHub</h5>
                <p class="mb-4">Your home for everything TCG related!</p>
            </div>
            <div class="col-lg-8 col-md-12">
                <div class="row">
                    <div class="col-md-8 mb-3">
                        <h5 class="text-secondary mb-4">Contacts</h5>
                        <p class="mb-2"><i class="fa fa-map-marker-alt text-primary mr-3"></i>Rua General Norton de Matos,  Apartado 4133 Leiria, Portugal</p>
                        <p class="mb-2"><i class="fa fa-envelope text-primary mr-3"></i>info@cardhub.com</p>
                        <p class="mb-0"><i class="fa fa-phone-alt text-primary mr-3"></i>+351 345 67890</p>
                    </div>
                    <div class="col-md-4 mb-3">
                        <h5 class="text-secondary mb-4">Me</h5>
                        <?php
                        if (Yii::$app->user->isGuest) {
                            echo Html::tag('div',
                                Html::a('<i class="fa fa-angle-right mr-2"></i>Log In', ['/site/login'], ['class' => 'text-secondary mb-2']),
                                ['class' => 'd-flex flex-column justify-content-start']
                            );
                            echo Html::tag('div',
                                Html::a('<i class="fa fa-angle-right mr-2"></i>Sign Up', ['/site/signup'], ['class' => 'text-secondary mb-2']),
                                ['class' => 'd-flex flex-column justify-content-start']
                            );
                        } else {
                            echo Html::tag('div',
                                Html::a('<i class="fa fa-angle-right mr-2"></i>My Account', ['/detail/details', 'id' => Yii::$app->user->id], ['class' => 'text-secondary mb-2']),
                                ['class' => 'd-flex flex-column justify-content-start']
                            );
                            echo Html::tag('div',
                                Html::a('<i class="fa fa-angle-right mr-2"></i>My Favorites', ['/favorite/index'], ['class' => 'text-secondary mb-2']),
                                ['class' => 'd-flex flex-column justify-content-start']
                            );
                            echo Html::tag('div',
                                Html::a('<i class="fa fa-angle-right mr-2"></i>My Cart', ['/cart/index'], ['class' => 'text-secondary mb-2']),
                                ['class' => 'd-flex flex-column justify-content-start']
                            );
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="row border-top mx-xl-5 py-3" style="border-color: rgba(256, 256, 256, .1) !important;">
            <div class="col-lg-9 col-md-7 px-xl-0 mt-3">
                <p class="mb-md-0 text-center text-md-left text-secondary">
                    &copy; 2025 <a class="text-primary" href="<?= \yii\helpers\Url::home() ?>">CardHub</a>. All Rights Reserved.
                </p>
            </div>
            <div class="col-lg-3 col-md-5 px-xl-0 text-center text-md-right">
                <div class="d-flex" style="justify-content: center">
                    <h6 class="text-secondary text-uppercase mt-3 mr-2">Follow Us:</h6>
                    <a class="btn btn-primary btn-square mr-2 text-center text-md-right" href="https://x.com/" target="_blank"><i class="fab fa-twitter text-dark"></i></a>
                    <a class="btn btn-primary btn-square mr-2 text-center text-md-right" href="https://www.facebook.com/" target="_blank"><i class="fab fa-facebook-f text-dark"></i></a>
                    <a class="btn btn-primary btn-square mr-2 text-center text-md-right" href="https://www.linkedin.com/" target="_blank"><i class="fab fa-linkedin-in text-dark"></i></a>
                    <a class="btn btn-primary btn-square text-center text-md-right" href="https://www.instagram.com/" target="_blank"><i class="fab fa-instagram text-dark"></i></a>
                </div>
            </div>
        </div>
    </div>
    <!-- Footer End -->

    <!-- JavaScript Libraries -->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.bundle.min.js"></script>
    <script src="lib/easing/easing.min.js"></script>
    <script src="lib/owlcarousel/owl.carousel.min.js"></script>

    <!-- Contact Javascript File -->
    <script src="mail/jqBootstrapValidation.min.js"></script>
    <script src="mail/contact.js"></script>

    <!-- Template Javascript -->
    <script src="web/frontend/web/js/main.js"></script>

<style>
    main.flex-shrink-0 {
        flex: 1 0 auto;
    }

    .container-fluid.bg-dark {
        flex-shrink: 0;
        margin-top: auto !important;
    }
</style>