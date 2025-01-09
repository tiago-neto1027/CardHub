<?php

namespace common\tests\unit\models;

use common\models\Card;
use common\models\Game;
use common\models\Listing;
use common\models\User;
use Yii;
use Codeception\Test\Unit;

class ListingTest extends Unit
{
    protected $tester;
    protected $listing;
    protected $game;

    // This method is called before each test
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

        // Create a new User if one doesn't exist
        $user = User::find()->one();
        if (!$user) {
            $user = new User();
            $user->username = 'testUser';
            $user->email = 'testuser@example.com';
            $user->password_hash = Yii::$app->security->generatePasswordHash('password');
            $user->save();
        }

        // Create a new Card if one doesn't exist
        $card = Card::find()->one();
        if (!$card) {
            $card = new Card();
            $card->game_id = $this->game->id; // Use a valid game_id
            $card->name = 'Test Card';
            $card->rarity = 'Rare';
            $card->image_url = 'http://example.com/image.jpg';
            $card->status = 'active';
            $card->save();
        }

        // Create a new Listing for the user and card
        $this->listing = new Listing();
        $this->listing->seller_id = $user->id;
        $this->listing->card_id = $card->id;
        $this->listing->price = 100.0;
        $this->listing->condition = 'Brand new';
        $this->listing->status = 'active';
        $this->listing->save();
    }

    // Test for checking if the listing's price is correctly set
    public function testPriceIsSetCorrectly()
    {
        // Retrieve the saved listing
        $listing = Listing::findOne($this->listing->id);

        // Assert that the price is set correctly
        $this->assertEquals(100.0, $listing->price);
    }

    // Test for checking if the listing's condition is correctly set
    public function testConditionIsSetCorrectly()
    {
        // Retrieve the saved listing
        $listing = Listing::findOne($this->listing->id);

        // Assert that the condition is set to 'New'
        $this->assertEquals('Brand new', $listing->condition);
    }

    // Test for checking the seller relation (if listing has a seller)
    public function testGetSellerRelation()
    {
        // Retrieve the saved listing
        $listing = Listing::findOne($this->listing->id);

        // Assert that the seller related to this listing is a User object
        $this->assertInstanceOf(User::class, $listing->seller);
    }

    // Test for checking the card relation (if listing has a card)
    public function testGetCardRelation()
    {
        // Retrieve the saved listing
        $listing = Listing::findOne($this->listing->id);

        // Assert that the card related to this listing is a Card object
        $this->assertInstanceOf(Card::class, $listing->card);
    }

    // Test for checking the listing's status is 'active'
    public function testStatusIsActive()
    {
        // Retrieve the saved listing
        $listing = Listing::findOne($this->listing->id);

        // Assert that the listing status is 'active'
        $this->assertEquals('active', $listing->status);
    }

    // Test for checking the sold listings count
    public function testGetSoldListingsCount()
    {
        // Create a sold listing for the count test
        $soldListing = new Listing();
        $soldListing->seller_id = $this->listing->seller_id;
        $soldListing->card_id = $this->listing->card_id;
        $soldListing->price = 150.0;
        $soldListing->condition = 'Used';
        $soldListing->status = 'sold';
        $soldListing->save();

        // Check the count of sold listings
        $soldCount = Listing::getSoldListingsCount();

        // Assert that the sold count is 1 (since we created one sold listing)
        $this->assertEquals(1, $soldCount);
    }

    // Test for checking if the status is set to 'sold' after changing
    public function testUpdateStatusToSold()
    {
        // Retrieve the saved listing
        $listing = Listing::findOne($this->listing->id);

        // Change the status of the listing to 'sold'
        $listing->status = 'sold';
        $listing->save();

        // Assert that the status is now 'sold'
        $this->assertEquals('sold', $listing->status);
    }

    // Test for checking if a listing is saved correctly (integration test)
    public function testListingIsSaved()
    {
        // Retrieve the saved listing
        $listing = Listing::findOne($this->listing->id);

        // Assert that the listing is not null, which means it was saved
        $this->assertNotNull($listing);
    }

    // Test for ensuring validation works (invalid data should fail)
    public function testInvalidListing()
    {
        // Create a new listing without a price (should be required)
        $invalidListing = new Listing();
        $invalidListing->seller_id = $this->listing->seller_id;
        $invalidListing->card_id = $this->listing->card_id;
        $invalidListing->condition = 'New';
        // $invalidListing->price is missing, so it will fail validation

        // Assert that the listing is not saved and validation fails
        $this->assertFalse($invalidListing->save());
        $this->assertNotEmpty($invalidListing->errors);
    }
}
