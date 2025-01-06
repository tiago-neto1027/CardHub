<?php
return [
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'components' => [
        'cache' => [
            'class' => \yii\caching\FileCache::class,

        ],
        'authManager' => [
            'class' => 'yii\rbac\DbManager',
            // Uncomment if you want to cache RBAC items hierarchy
        ],

        // ... other components
    ],


];

