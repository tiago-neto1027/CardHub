<?php

namespace frontend\tests\functional;

use common\fixtures\CardFixture;
use common\fixtures\GameFixture;
use common\fixtures\UserFixture;
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
        ];
    }

    public function testAddToFavorites(FunctionalTester $I)
    {
        $I->amOnPage('/site/login');
        $I->fillField('LoginForm[username]', 'test.test');
        $I->fillField('LoginForm[password]', 'Test1234');
        $I->click('Login');

        $I->amOnPage('/listing/index');
        $I->seeInCurrentUrl('/listing/index');

        $I->seeElement('a.btn.btn-outline-dark.btn-square i.far.fa-heart');

        $I->click('a.btn.btn-outline-dark.btn-square i.far.fa-heart');

        $I->amOnPage('/listing/index');
        $I->see('fas fa-heart-broken', );
    }

}