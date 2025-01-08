<?php

namespace common\tests\unit\models;

use common\models\Favorite;
use common\models\Card;
use common\models\User;
use Codeception\Test\Unit;

class FavoriteTest extends Unit
{
    private $user;
    private $card;
    private $favorite;

    // Set up the necessary data for the tests
    protected function _before()
    {
        // Create a user and a card to associate with the favorite
        $this->user = new User();
        $this->user->username = 'userexample';
        $this->user->email = "user@example.com";
        $this->user->password = "password123";
        $this->user->save();

        $this->card = new Card();
        $this->card->game_id = 1;
        $this->card->name = "Test Card";
        $this->card->rarity = "Rare";
        $this->card->image_url = "http://example.com/test.jpg";
        $this->card->status = "active";
        $this->card->save();

        // Create a favorite entry linking the user and the card
        $this->favorite = new Favorite();
        $this->favorite->user_id = $this->user->id;
        $this->favorite->card_id = $this->card->id;
        $this->favorite->save();
    }

    // Test to verify if the Favorite model is saved correctly
    public function testFavoriteIsSavedCorrectly()
    {
        $this->assertTrue($this->favorite->save(), 'Favorite should be saved correctly.');
    }

    // Test validation - ensure user_id and card_id are required
    public function testFavoriteValidation()
    {
        $favorite = new Favorite();

        // Test missing user_id
        $favorite->card_id = $this->card->id;
        $this->assertFalse($favorite->validate(), 'Favorite should not be valid without user_id');

        // Test missing card_id
        $favorite->user_id = $this->user->id;
        $this->assertFalse($favorite->validate(), 'Favorite should not be valid without card_id');
    }

    // Test the relationship with the Card model
    public function testCardRelation()
    {
        $favorite = Favorite::findOne($this->favorite->id);
        $this->assertNotNull($favorite->card, 'Favorite should have a related card.');
        $this->assertEquals($this->card->id, $favorite->card->id, 'The related card should match the one in the favorite.');
    }

    // Test the relationship with the User model
    public function testUserRelation()
    {
        $favorite = Favorite::findOne($this->favorite->id);
        $this->assertNotNull($favorite->user, 'Favorite should have a related user.');
        $this->assertEquals($this->user->id, $favorite->user->id, 'The related user should match the one in the favorite.');
    }

    // Test to check if a user can favorite a card
    public function testUserCanFavoriteCard()
    {
        $favorite = Favorite::find()->where(['user_id' => $this->user->id, 'card_id' => $this->card->id])->one();
        $this->assertNotNull($favorite, 'User should be able to favorite a card.');
    }

    // Test to ensure there are no duplicates in the favorites (a user cannot favorite the same card more than once)
    public function testDuplicateFavorite()
    {
        $duplicateFavorite = new Favorite();
        $duplicateFavorite->user_id = $this->user->id;
        $duplicateFavorite->card_id = $this->card->id;
        $this->assertFalse($duplicateFavorite->save(), 'A user should not be able to favorite the same card more than once.');
    }

    // Test to ensure a favorite entry can be deleted
    public function testDeleteFavorite()
    {
        $this->favorite->delete();
        $favorite = Favorite::findOne($this->favorite->id);
        $this->assertNull($favorite, 'Favorite should be deleted successfully.');
    }

    // Test to check the user_id and card_id fields
    public function testUserAndCardFields()
    {
        $favorite = Favorite::findOne($this->favorite->id);
        $this->assertEquals($this->user->id, $favorite->user_id, 'user_id should be correctly set.');
        $this->assertEquals($this->card->id, $favorite->card_id, 'card_id should be correctly set.');
    }
}
