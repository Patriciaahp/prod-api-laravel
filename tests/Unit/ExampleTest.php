<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Models\User;
use App\Http\Controllers\Api\AuthController;
class ExampleTest extends TestCase
{

    /**
     * A basic feature test example.
     * @test
     * Command for testing: vendor\bin\phpunit --filter=
     * @return void
     */
    public function users_assert_store_ok()
    {

        $data = array(
            'name' => $this->faker->name,
            'email' => $this->faker->safeEmail,
            'password' => 123456
        );

        $user = User::register($data);
       

        $this->assertNotNull($user);

        $this->assertDatabaseHas($user->getTable(), [
            'id' => $user->id
        ]);

        $credentials = [
            'email' => $data['email'],
            'password' => 123456
        ];

        Auth::once($credentials);
        $this->assertAuthenticated();

        $this->assertDatabaseHas($user->getTable(), [
            'id' => $user->id
        ]);
    }

    public function users_assert_login_ok()
    {

        $data = array(
            'name' => $this->faker->name,
            'email' => $this->faker->safeEmail,
            'password' => 123456
        );

        $user = User::create($data);
       

        $this->assertNotNull($user);

        $this->assertDatabaseHas($user->getTable(), [
            'id' => $user->id
        ]);

        $credentials = [
            'email' => $data['email'],
            'password' => 123456
        ];

        Auth::login($credentials);

        $this->assertAuthenticated();

        $this->assertDatabaseHas($user->getTable(), [
            'id' => $user->id
        ]);
    }

}
