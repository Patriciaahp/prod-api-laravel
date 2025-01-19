<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

class product_test extends TestCase
{
    /**
     * A basic unit test example.
     */
    public function test_example(): void
    {
        $this->assertTrue(true);
    }

    public function test_product_creation()
{
    $response = $this->postJson('/api/products', [
        'name' => 'Producto Test',
        'price' => 100,
        'stock' => 50
    ]);

    $response->assertStatus(201)
             ->assertJsonStructure([
                 'id', 'name', 'price', 'stock', 'description', 'categories'
             ]);
}
}
