<?php

namespace Tests\Unit\Models;

use App\Models\Product;
use Tests\TestCase;
use Tests\Traits\SetupDatabaseMigrations;

class ProductTest extends TestCase
{
    use SetupDatabaseMigrations;

    protected function setUp(): void
    {
        parent::setUp();

    }

    public function test_can_product()
    {
        Product::create(['name' => 'Test coffee']);
        $product = Product::latest()->first();
        $this->assertNotEmpty($product);
        $this->assertEquals($product['name'], $product->name);
    }
}
