<?php

namespace backend\tests\functional;

use backend\tests\FunctionalTester;
use common\fixtures\UserFixture;
use Yii;

/**
 * Class LoginCest
 */
class LoginCest
{
    /**
     * Load fixtures before db transaction begin
     * Called in _before()
     * @see \Codeception\Module\Yii2::_before()
     * @see \Codeception\Module\Yii2::loadFixtures()
     * @return array
     */
    public function _fixtures()
    {
        return [
            'user' => [
                'class' => UserFixture::class,
                'dataFile' => codecept_data_dir() . 'login_data.php'
            ]
        ];
    }


    /**
     * @param FunctionalTester $I
     */

    public function loginWithValidCredentials(FunctionalTester $I)
    {
        $I->amOnRoute('/site/login');
        $I->seeElement('#login-button');

        $I->fillField('LoginForm[username]', 'T3st Us3r');
        $I->fillField('LoginForm[password]', 'password_0');
        $I->click('#login-button');

        $I->dontSeeElement('#login-button');

        $I->seeElement('#logout-button');
    }

    public function loginWithInvalidCredentials(FunctionalTester $I)
    {
        $I->amOnRoute('/site/login');

        $I->seeElement('#login-button');

        $I->fillField('LoginForm[username]', 'invalid_user');
        $I->fillField('LoginForm[password]', 'wrong_password');
        $I->click('#login-button');

        $I->seeElement('#login-button');

        $I->amOnRoute('/site/login');
    }

}
