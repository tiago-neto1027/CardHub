<?php

namespace frontend\tests\functional;

use common\fixtures\CardFixture;
use common\fixtures\GameFixture;
use common\fixtures\UserFixture;
use common\fixtures\ListingFixture;
use common\models\User;
use frontend\tests\FunctionalTester;
use Yii;

class FavoriteCest
{
    public function _fixtures()
    {
        return [
            'game' => [
                'class' => GameFixture::class,
                'dataFile' => codecept_data_dir() . 'game.php',
            ],
            'user' => [
                'class' => UserFixture::class,
                'dataFile' => codecept_data_dir() . 'user.php',
            ],
            'card' => [
                'class' => CardFixture::class,
                'dataFile' => codecept_data_dir() . 'card.php',
            ],
            'listing' => [
                'class' => ListingFixture::class,
                'dataFile' => codecept_data_dir() . 'listing.php',
            ]
        ];
    }

    public function testAddToFavorites(FunctionalTester $I)
    {
        //Log In
        $I->amOnPage('/site/login');
        $I->see("Please fill out the following fields to login:");
        $I->submitForm('#login-form', [
            'LoginForm[username]' => 'user_seller',
            'LoginForm[password]' => 'sellerpassword'
        ]);
        $I->dontSeeElement('#login-button');
        $I->seeElement('#logout-button');

        //Click on the Listings dropdown and choose an option
        $I->click('#listings-dropdown');
        $I->click('a.dropdown-item');
        $I->see('PRODUCT CATALOG');
        $I->dontSee('No listings available at the moment');

        //Click on the heart icon to add to favorites
        $I->seeElement('i#favorite-heart');
        $I->click('i#favorite-heart');

        //After adding to favorites, click the heart icon to navigate to the favorites page
        $I->amOnPage('/favorite/index');
        $I->seeInCurrentUrl('/favorite/index');
    }
}