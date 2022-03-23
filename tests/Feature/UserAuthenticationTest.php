<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Tests\TestCase;

class UserAuthenticationTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic unit test example.
     *
     * @return void
     */

    public function testSuccessfulLogin()
    {
        $user = User::factory()->create([
            'email' => 'sample@test.com',
            'password' => bcrypt('sample123'),
        ]);

        $loginData = ['email' => 'sample@test.com', 'password' => 'sample123'];
        $this->post('/api/v1/login', $loginData)
            ->assertStatus(Response::HTTP_ACCEPTED)
            ->assertJsonStructure([
                "data" => [
                    'id',
                    'first_name',
                    'last_name',
                    'role_id',
                    'email',
                    'email_verified_at',
                    'created_at',
                    'updated_at',
                    'access_token',
                ],
                "message",
            ]);

        $this->assertAuthenticated();
    }

    public function testLoginWithWrongDetails()
    {

        $loginData = ['email' => 'sample@test.com', 'password' => 'sample123gvbhj'];
        $this->post('/api/v1/login', $loginData)
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }
}
