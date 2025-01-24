<?php

use App\Models\Product;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use Laravel\Sanctum\Sanctum;

class ProductTest extends TestCase
{
	use RefreshDatabase;

	public function test_store_ok()
	{
        $user = User::factory()->create();
        Sanctum::actingAs($user);
    	$data = [
        	'name' => 'New Product',
        	'price' => 100.00,
        	'stock' => 10,
    	];

    	$response = $this->postJson('/api/products', $data);

    	$response->assertStatus(201)
             	->assertJson(['name' => 'New Product']);
    	$this->assertDatabaseHas('products', ['name' => 'New Product']);
	}

	public function test_store_ko()
	{
        $user = User::factory()->create();
        Sanctum::actingAs($user);
    	$data = ['name' => '', 'price' => 'invalid', 'stock' => 'invalid'];

    	$response = $this->postJson('/api/products', $data);

    	$response->assertStatus(422)
             	->assertJsonValidationErrors(['name', 'price', 'stock']);
	}

	public function test_show_ok()
	{
        $user = User::factory()->create();
        Sanctum::actingAs($user);
    	$product = Product::factory()->create();

    	$response = $this->getJson("/api/products/{$product->id}");

    	$response->assertStatus(200)
             	->assertJson(['id' => $product->id, 'name' => $product->name]);
	}

	public function test_show_ko()
	{
        $user = User::factory()->create();
        Sanctum::actingAs($user);
    	$response = $this->getJson('/api/products/999');

    	$response->assertStatus(404)
             	->assertJson(['message' => 'Producto no encontrado']);
	}

	public function test_update_ok()
	{
        $user = User::factory()->create();
        Sanctum::actingAs($user);
    	$product = Product::factory()->create();
    	$data = ['name' => 'Updated Product', 'price' => 150.00, 'stock' => 20];

    	$response = $this->putJson("/api/products/{$product->id}", $data);

    	$response->assertStatus(200)
             	->assertJson(['name' => 'Updated Product']);
    	$this->assertDatabaseHas('products', ['name' => 'Updated Product']);
	}

	public function test_assign_categories_ok()
	{
        $user = User::factory()->create();
        Sanctum::actingAs($user);
    	$product = Product::factory()->create();
    	$category = Category::factory()->create();

    	$response = $this->postJson("/api/products/{$product->id}/categories", [
        	'category_ids' => [$category->id],
    	]);

    	$response->assertStatus(200)
             	->assertJson(['message' => 'Categorías asignadas correctamente.']);
    	$this->assertDatabaseHas('category_product', [
        	'product_id' => $product->id,
        	'category_id' => $category->id,
    	]);
	}

	public function test_assign_categories_ko()
	{
        $user = User::factory()->create();
        Sanctum::actingAs($user);
    	$response = $this->postJson('/api/products/999/categories', [
        	'category_ids' => [1],
    	]);

    	$response->assertStatus(404)
             	->assertJson(['message' => 'Producto no encontrado.']);
	}


	public function test_destroy_ok()
	{
        $user = User::factory()->create();
        Sanctum::actingAs($user);
    	$product = Product::factory()->create();

    	$response = $this->deleteJson("/api/products/{$product->id}");

    	$response->assertStatus(200)
             	->assertJson(['message' => 'Producto eliminado']);
    	$this->assertDatabaseMissing('products', ['id' => $product->id]);
	}
}
