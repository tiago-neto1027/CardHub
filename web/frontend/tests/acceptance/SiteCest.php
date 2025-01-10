<?php

namespace frontend\tests\acceptance;

use common\fixtures\UserFixture;
use common\models\User;
use Facebook\WebDriver\WebDriverKeys;
use frontend\tests\AcceptanceTester;
use Yii;

class SiteCest
{
    public function _fixtures()
    {
        return [
            'user' => [
                'class' => UserFixture::class,
                'dataFile' => codecept_data_dir() . 'user.php',
            ],
        ];
    }

    public function testFullSite(AcceptanceTester $I)
    {
        $I->amOnPage('/site/login');
        $I->see("Please fill out the following fields to login:");

        $I->submitForm('#login-form', [
            'LoginForm[username]' => 'sample_user',
            'LoginForm[password]' => 'samplepassword'
        ]);
        $I->wait(2);


        $I->amOnPage('/listing/index');
        $I->seeInCurrentUrl('/listing/index');
        $I->seeElement('#create_listing');
        $I->wait(2);

        $I->amOnPage('/listing/create');
        $I->seeInCurrentUrl('/listing/create');
        $I->see('Create new Card');
        $I->wait(2);

        $I->click('#autocomplete-card-name');
        $I->type('Dark');
        $I->wait(2);
        $I->click('.ui-menu-item-wrapper');
        $I->fillField('#listing-price', '4.80');
        $I->selectOption('#listing-condition', 'Brand new');
        $I->click('Save');
        $I->wait(5);

        $I->seeElement('#create_listing');

        $I->click('#logout-button');

        $I->amOnPage('/site/login');

        $I->submitForm('#login-form', [
            'LoginForm[username]' => 'UserTeste',
            'LoginForm[password]' => 'UserTeste'
        ]);

        $I->wait(2);

        $I->amOnPage('catalog/index?game=Yu-Gi-Oh%21&type=listing');
        $I->wait(5);

        $I->moveMouseOver('.product-item');
        $I->scrollTo('.product-item');
        $I->seeElement('i#favorite-heart');
        $I->click('i#favorite-heart');
        $I->wait(3);

        $I->amOnPage('/favorite/index');
        $I->wait(5);
    }
}