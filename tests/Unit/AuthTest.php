<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Tests\TestCase;

class AuthTest extends TestCase
{
	use RefreshDatabase;

	public function test_register_ok()
	{
    	$data = [
        	'name' => 'Test User',
        	'email' => 'test@example.com',
        	'password' => 'password123',
    	];

    	$response = $this->postJson('/api/register', $data);

    	$response->assertStatus(201)
             	->assertJson(['message' => 'Usuario registrado con éxito']);

    	$this->assertDatabaseHas('users', [
        	'email' => 'test@example.com',
    	]);
	}

	public function test_register_ko()
	{
    	$data = [
        	'name' => '',
        	'email' => 'hola',
        	'password' => '123',
    	];

    	$response = $this->postJson('/api/register', $data);

    	$response->assertStatus(422);
    	$response->assertJsonValidationErrors(['name', 'email', 'password']);
	}

	public function test_login_ok()
	{
    	$user = User::factory()->create([
        	'password' => Hash::make('password123')
    	]);

    	$data = [
        	'email' => $user->email,
        	'password' => 'password123',
    	];

    	$response = $this->postJson('/api/login', $data);

    	$response->assertStatus(200)
             	->assertJsonStructure(['token']);
	}

	public function test_login_ko()
	{
    	$user = User::factory()->create();

    	$data = [
        	'email' => $user->email,
        	'password' => '123',
    	];

    	$response = $this->postJson('/api/login', $data);

    	$response->assertStatus(401)
             	->assertJson(['message' => 'Credenciales incorrectas']);
	}

	public function test_logout_ok()
	{
    	$user = User::factory()->create();
    	$token = $user->createToken('API Token')->plainTextToken;

    	$response = $this->withHeaders([
        	'Authorization' => "Bearer $token"
    	])->postJson('/api/logout');

    	$response->assertStatus(200)
             	->assertJson(['message' => 'Sesión cerrada con éxito']);
	}
}
