<?php

namespace Tests\Unit;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_register_successfully(): void
    {
        $data = [
            'name' => 'John Smith',
            'email' => 'john@gmail.com',
            'password' => 'abcd1234'
        ];

        $response = $this->postJson('/api/register', $data);

        $response->assertStatus(201);
        $response->assertJson([
            'message' => 'User registered successfully'
        ]);
        $this->assertDatabaseHas('users', ['email'=>'john@gmail.com']);
    }
    public function test_user_login_successfully(): void
    {
        $user = User::factory()->create([
            'email' => 'john@gmail.com',
            'password' => 'abcd1234'
        ]);

        $response = $this->postJson('/api/login',['email' => 'john@gmail.com', 'password' => 'abcd1234']);

        $response->assertStatus(200);
        $response->assertJson([
            'message' => 'Logged in successfully'
        ]);
    }
}
