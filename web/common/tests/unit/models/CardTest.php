<?php

namespace tests\unit;

use Codeception\Test\Unit;
use common\models\Card;
use common\models\Game;
use common\models\User;
use common\models\Favorite;
use common\models\Listing;
use Yii;
use yii\base\InvalidConfigException;

class CardTest extends Unit
{
    protected $tester;

    protected $game;

    protected function _before()
    {
        //Creating Game for tests
        $this->game = Game::find()->one();
        if (!$this->game) {
            $this->game = new Game();
            $this->game->name = 'Sample Game';
            $this->game->logo_url = 'http://teste.com';
            $this->game->save();
        }
        //Creating User for tests
        $user = User::find()->one();
        if (!$user) {
            $user = new User();
            $user->username = 'sampleUser';
            $user->email = 'sample@user.com';
            $user->password_hash = Yii::$app->getSecurity()->generatePasswordHash('password');
            $user->save();
        }

        //Creating Card for tests
        $this->card = new Card();
        $this->card->game_id = $this->game->id;
        $this->card->name = "Test Card2";
        $this->card->rarity = "Rare";
        $this->card->image_url = "http://example.com/image.jpg";
        $this->card->status = "active";
        $this->card->user_id = $user->id;
        $this->card->save();
        var_dump($this->card);
    }

    protected function _after()
    {
    }

    public function testValidation()
    {
        $card = new Card();

        $this->assertFalse($card->validate());


        $card->game_id = $this->game->id;
        $card->name = "Card Name";
        $card->rarity = "Rare";
        $card->image_url = "http://example.com/image.jpg";
        $card->status = "active";
        $card->description = 'teste';
        $card->user_id = null;

        $this->assertTrue($card->validate());
    }

    public function testGetPendingCardCount()
    {
        $count = Card::getPendingCardCount();

        $this->assertGreaterThanOrEqual(0, $count);
    }

    public function testGetListingsCount()
    {
        $card = Card::find()->one();
        $count = $card->getListingsCount();

        $this->assertGreaterThanOrEqual(0, $count);
    }

    public function testGetActiveListingsCount()
    {
        $card = Card::find()->one();
        $count = $card->getActiveListingsCount();

        $this->assertGreaterThanOrEqual(0, $count);
    }

    public function testIsFavorited()
    {
        $card = Card::find()->one();
        $userId = Yii::$app->user->getId();

        $isFavorited = $card->isFavorited();

        $this->assertIsBool($isFavorited);
    }

    public function testGetGameRelation()
    {
        $card = Card::find()->one();
        $game = $card->game;

        $this->assertInstanceOf(Game::class, $game);
    }

    public function testGetUserRelation()
    {
        $card = Card::find()->one();
        $user = $card->user;

        $this->assertInstanceOf(User::class, $user);
    }


    public function testGetListingsRelation()
    {
        $card = Card::find()->one();
        $listings = $card->listings;

        $this->assertIsArray($listings);
    }

    public function testTimestampBehavior()
    {
        $card = new Card();
        $card->game_id = $this->game->id;
        $card->name = "New Card";
        $card->rarity = "Epic";
        $card->image_url = "http://example.com/epic-card.jpg";
        $card->status = "active";

        $card->save();
        echo('>>>>>>>>>>>>>>>>>>>>>>>>>>'.$card->name);
        $this->assertNotNull($card->created_at);
        $this->assertNotNull($card->updated_at);
    }
}