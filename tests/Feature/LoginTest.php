<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    protected $password = 'my-testing-password';

    public function test_user_can_login_with_correct_credentials(): void
    {
        $user = User::factory()->create([
            'password' => Hash::make($this->password)
        ]);

        $response = $this->post('/api/login', [
            'email' => $user->email,
            'password' => $this->password,
        ]);

        $response->assertOk();

        $this->actingAs($user);

        $this->assertAuthenticatedAs($user);
    }

    public function test_user_cannot_login_with_wrong_credentials(): void
    {
        $user = User::factory()->create([
            'password' => Hash::make($this->password)
        ]);

        $response = $this->post('/api/login', [
            'email' => $user->email,
            'password' => 'wrong-password',
        ]);

        $response->assertStatus(422);
    }

    public function test_equired_user_email(): void
    {
        $user = User::factory()->create([
            'password' => Hash::make($this->password)
        ]);

        $response = $this->post('/api/login', [
            'password' => $this->password,
        ]);

        $response->assertStatus(422);
    }
    public function test_required_user_password(): void
    {
        $user = User::factory()->create([
            'password' => Hash::make($this->password)
        ]);

        $response = $this->post('/api/login', [
            'email' => $user->email,
        ]);

        $response->assertStatus(422);
    }
}
