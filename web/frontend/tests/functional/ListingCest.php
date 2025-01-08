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
        $I->fillField('LoginForm[username]', 'seller_user');
        $I->fillField('LoginForm[password]', 'password');
        $I->click('Login');

        $I->amOnPage('/listing/index');
        $I->seeInCurrentUrl('/listing/index');
        $I->seeElement('#create_listing');

        $I->amOnPage('/listing/create');
        $I->seeInCurrentUrl('/listing/create');
        $I->see('#create_card');
        $I->click('#create_card');

        $I->fillField('Card[name]', 'Test Card');
        $I->fillField('Card[rarity]', 'Common');
        $I->fillField('Card[image_url]', 'http://teste.png');
        $I->fillField('Card[description]', 'This is a test card description.');

        $I->selectOption('Card[game_id]', 1);

        $I->click('Save');

        $I->see('Card created successfully');
        $I->amOnPage('/listing/index');
    }


}