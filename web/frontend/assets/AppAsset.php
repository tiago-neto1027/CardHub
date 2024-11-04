<?php

namespace frontend\assets;

use yii\web\AssetBundle;

/**
 * Main frontend application asset bundle.
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/site.css',
        'css/style.css',
        'lib/animate/animate.min.css',
        'lib/owlcarousel/assets/owl.carousel.min.css',
    ];
    public $js = [
        'lib/easing/easing.min.js',
        'lib/owlcarousel/owl.carousel.min.js',
        'js/main.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap5\BootstrapAsset',
    ];
}
