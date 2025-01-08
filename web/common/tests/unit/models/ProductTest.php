<?php

namespace common\tests\unit\models;

use common\models\Product;
use common\models\Game;
use Codeception\Test\Unit;

class ProductTest extends Unit
{
    private $game;
    private $product;

    protected function _before()
    {
        // Create a game record for the product
        $this->game = new Game();
        $this->game->name = "Test Game";
        $this->game->logo_url = "http://example.com/game.jpg";
        $this->game->save();

        // Create a product for the game
        $this->product = new Product();
        $this->product->game_id = $this->game->id;
        $this->product->name = "Test Product";
        $this->product->price = 19.99;
        $this->product->stock = 50;
        $this->product->status = "active";
        $this->product->image_url = "http://example.com/product.jpg";
        $this->product->type = "physical";
        $this->product->description = "A test product for the test game";
        $this->product->save();
    }

    public function testProductValidation()
    {
        $product = new Product();

        // Test missing required fields
        $this->assertFalse($product->validate(), 'Product should not be valid without required fields.');

        // Test valid product
        $product->game_id = $this->game->id;
        $product->name = "Valid Product";
        $product->price = 29.99;
        $product->stock = 100;
        $product->status = "active";
        $product->image_url = "http://example.com/valid-product.jpg";
        $product->type = "booster";
        $product->description = "A valid product";
        $this->assertTrue($product->validate(), 'Product should be valid with all required fields.');
    }

    public function testGetStock()
    {
        // Test stock retrieval
        $stock = Product::getStock($this->product->id);
        $this->assertEquals(50, $stock, 'Stock count should be 50.');
    }

    public function testGetProductTypes()
    {
        // Test getting all product types
        $types = Product::getProductTypes();
        $this->assertContains('booster', $types, 'Booster type should be in the list of product types.');
    }

    public function testGetSoldProductsCount()
    {
        // Assuming we have invoice_lines with product transactions
        $soldCount = Product::getSoldProductsCount();
        $this->assertGreaterThanOrEqual(0, $soldCount, 'Sold products count should be a non-negative number.');
    }

    public function testGetTotalRevenue()
    {
        // Test total revenue calculation
        $totalRevenue = Product::getTotalRevenue();
        $this->assertNotNull($totalRevenue, 'Total revenue should not be null.');
    }

    public function testGetLowStockCount()
    {
        // Assuming the product stock is 50, and we have some products with stock < 10
        $lowStockCount = Product::getLowStockCount();
        $this->assertGreaterThanOrEqual(0, $lowStockCount, 'Low stock count should be a non-negative number.');
    }

    public function testGetNoStockCount()
    {
        // Assuming we have some products with stock = 0
        $noStockCount = Product::getNoStockCount();
        $this->assertGreaterThanOrEqual(0, $noStockCount, 'No stock count should be a non-negative number.');
    }
}
