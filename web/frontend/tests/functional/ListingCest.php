<?php

namespace frontend\tests\functional;

use common\fixtures\CardFixture;
use common\fixtures\GameFixture;
use common\fixtures\UserFixture;
use common\models\User;
use frontend\tests\FunctionalTester;
use Yii;

class ListingCest
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
        ];
    }

    public function testCreateCardWithValidData(FunctionalTester $I)
    {
        $I->amOnPage('/site/login');
        $I->see("Please fill out the following fields to login:");
        $I->submitForm('#login-form', [
            'LoginForm[username]' => 'user_seller',
            'LoginForm[password]' => 'sellerpassword'
        ]);
        $I->dontSeeElement('#login-button');
        $I->seeElement('#logout-button');

        $I->dontSee('Become a seller');
        $I->amOnPage('/listing/index');
        $I->seeInCurrentUrl('/listing/index');
        $I->seeElement('#create_listing');

        $I->amOnPage('/listing/create');
        $I->seeInCurrentUrl('/listing/create');
        $I->see('Create new Card');
        $I->click('#create_card');
        $I->see('Suggest a new card');

        $I->fillField('Card[name]', 'Test Card');
        $I->fillField('Card[rarity]', 'Common');
        $I->fillField('Card[image_url]', 'http://teste.png');
        $I->fillField('Card[description]', 'This is a test card description.');

        $I->selectOption('Card[game_id]', 1);

        $I->click('Save');

        $I->see('Card submitted');
        $I->amOnPage('/listing/index');
    }

    public function testCreateListing(FunctionalTester $I)
    {
        $I->amOnPage('/site/login');
        $I->see("Please fill out the following fields to login:");
        $I->submitForm('#login-form', [
            'LoginForm[username]' => 'user_seller',
            'LoginForm[password]' => 'sellerpassword'
        ]);
        $I->dontSeeElement('#login-button');
        $I->seeElement('#logout-button');

        $I->dontSee('Become a seller');
        $I->amOnPage('/listing/index');
        $I->seeInCurrentUrl('/listing/index');
        $I->seeElement('#create_listing');

        $I->amOnPage('/listing/create');
        $I->seeInCurrentUrl('/listing/create');
        $I->see('Create new Card');

        $I->fillField('#listing-card_id', 1);
        $I->fillField('#listing-price', '4.80');
        $I->selectOption('#listing-condition', 'Brand new');
        $I->click('Save');

        $I->seeInCurrentUrl('/listing/index');
        $I->see('TestCard');
        $I->see('4.80');
    }


}