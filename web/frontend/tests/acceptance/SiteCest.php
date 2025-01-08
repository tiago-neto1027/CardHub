<?php

namespace frontend\tests\acceptance;

class SiteCest
{
    public function testFullSite(AcceptanceTester $I)
    {
        $I->amOnPage('/site/login');
        $I->see("Please fill out the following fields to login:");

        $I->submitForm('#login-form', [
            'LoginForm[username]' => 'user_seller',
            'LoginForm[password]' => 'sellerpassword'
        ]);
        $I->dontSeeElement('#login-button');
        $I->seeElement('#logout-button');

        $I->amOnPage('/listing/index');
        $I->seeInCurrentUrl('/listing/index');
        $I->see('Your Listings');
        $I->seeElement('#create_listing');

        $I->amOnPage('/listing/create');
        $I->seeInCurrentUrl('/listing/create');
        $I->see('Create new Card');

        $I->fillField('#listing-card_id', 1);
        $I->fillField('#listing-price', '4.80');
        $I->selectOption('#listing-condition', 'Brand new');
        $I->click('Save');

        $I->seeInCurrentUrl('/listing/index');
        $I->see('Listing created successfully');
        $I->see('TestCard');
        $I->see('4.80');
        $I->see('Brand new');

        $I->click('#logout-button');
        $I->seeInCurrentUrl('/site/login');

        $I->submitForm('#login-form', [
            'LoginForm[username]' => 'user_buyer',
            'LoginForm[password]' => 'buyerpassword'
        ]);
        $I->dontSeeElement('#login-button');
        $I->seeElement('#logout-button');

        $I->amOnPage('/listing/index');
        $I->seeInCurrentUrl('/listing/index');
        $I->see('Your Listings');
        $I->see('TestCard');
        $I->see('4.80');

        $I->click('TestCard');
        $I->seeInCurrentUrl('/listing/view');
        $I->see('TestCard');
        $I->see('4.80');

        $I->click('#add-to-favorites');
        $I->see('Added to Favorites');
        
        $I->click('#logout-button');
        $I->seeInCurrentUrl('/site/login');
    }
}