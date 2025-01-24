<?php

use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use Laravel\Sanctum\Sanctum;

class CategoryTest extends TestCase
{
	use RefreshDatabase;

	public function test_index_ok()
	{
        $user = User::factory()->create();
        Sanctum::actingAs($user);
    	Category::factory()->count(5)->create();

    	$response = $this->getJson('/api/categories');

    	$response->assertStatus(200)
             	->assertJsonCount(5);
	}

	public function test_store_ok()
	{
        $user = User::factory()->create();
        Sanctum::actingAs($user);
    	$data = ['name' => 'New Category'];

    	$response = $this->postJson('/api/categories', $data);

    	$response->assertStatus(201)
             	->assertJson(['name' => 'New Category']);
    	$this->assertDatabaseHas('categories', ['name' => 'New Category']);
	}

	public function test_store_ko()
	{
        $user = User::factory()->create();
        Sanctum::actingAs($user);
    	$data = ['name' => ''];

    	$response = $this->postJson('/api/categories', $data);

    	$response->assertStatus(422)
             	->assertJsonValidationErrors(['name']);
	}

	public function test_show_ok()
	{
        $user = User::factory()->create();
        Sanctum::actingAs($user);
    	$category = Category::factory()->create();

    	$response = $this->getJson("/api/categories/{$category->id}");

    	$response->assertStatus(200)
             	->assertJson(['id' => $category->id, 'name' => $category->name]);
	}

	public function test_show_ko()
	{
        $user = User::factory()->create();
        Sanctum::actingAs($user);
    	$response = $this->getJson('/api/categories/999');

    	$response->assertStatus(404)
             	->assertJson(['message' => 'Categoría no encontrada']);
	}

	public function test_update_ok()
	{
        $user = User::factory()->create();
        Sanctum::actingAs($user);
    	$category = Category::factory()->create();
    	$data = ['name' => 'Updated Category'];

    	$response = $this->putJson("/api/categories/{$category->id}", $data);

    	$response->assertStatus(200)
             	->assertJson(['name' => 'Updated Category']);
    	$this->assertDatabaseHas('categories', ['name' => 'Updated Category']);
	}

	public function test_destroy_ok()
	{
        $user = User::factory()->create();
        Sanctum::actingAs($user);
    	$category = Category::factory()->create();

    	$response = $this->deleteJson("/api/categories/{$category->id}");

    	$response->assertStatus(200)
             	->assertJson(['message' => 'Categoría eliminada']);
    	$this->assertDatabaseMissing('categories', ['id' => $category->id]);
	}
}
