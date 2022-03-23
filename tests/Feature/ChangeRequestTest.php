<?php

namespace Tests\Feature;

use App\Models\ChangeRequest;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Tests\TestCase;

class ChangeRequestTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function testCreateRequestWithoutAuth()
    {
        $data = [
            'first_name' => "Okocha",
            'last_name' => "John Paul",
            'email' => 'dav1234@gmail.com',
            'role_id' => 1,
            'password' => "1234567890",
        ];
        $response = $this->json('POST', '/api/v1/request/create-user', $data);
        $response->assertStatus(401);
        $response->assertJson(['message' => "Unauthenticated."]);
    }

    public function testRequestUpdateUserWithAuth()
    {
        $data = [
            'first_name' => "Okocha",
            'last_name' => "John Paul",
            'email' => 'dav1234@gmail.com',
            'user_id' => "1",
            'role_id' => 2,
            'password' => "1234567890",

        ];
        $role = Role::factory()->create(['name' => 'Super Admin']);
        $user = User::factory()->create(['role_id' => $role->id]);
        $response = $this->actingAs($user)->post('/api/v1/request/update-user', $data);
        $response->assertStatus(Response::HTTP_CREATED)
            ->assertJson([
                "status" => true,
            ]);

    }

    public function testRequestDeleteUserWithAuth()
    {
        $data = [
            'user_id' => "1",

        ];
        $role = Role::factory()->create(['name' => 'Super Admin']);
        $user = User::factory()->create(['role_id' => $role->id]);
        $response = $this->actingAs($user)->post('/api/v1/request/delete-user', $data);
        $response->assertStatus(Response::HTTP_CREATED)
            ->assertJson([
                "status" => true,
            ]);

    }

    public function testViewAllRequestWithAuth()
    {
        $role = Role::factory()->create(['name' => 'Super Admin']);
        $user = User::factory()->create(['role_id' => $role->id]);
        $response = $this->actingAs($user)->get('/api/v1/change-request');
        $response->assertStatus(Response::HTTP_OK)
            ->assertJson([
                "status" => true,
            ]);

    }

    public function testViewSingleRequestWithAuth()
    {

        $role = Role::factory()->create(['name' => 'Super Admin']);
        $user = User::factory()->create(['role_id' => $role->id]);
        $changeRequest = ChangeRequest::factory()->create();
        $response = $this->actingAs($user)->get('/api/v1/change-request/' . $changeRequest->id);
        $response->assertStatus(Response::HTTP_FOUND)
            ->assertJson([
                "status" => true,
            ]);

    }

    public function testApproveRequestWithAuth()
    {

        $role = Role::factory()->create(['name' => 'Super Admin']);
        $user = User::factory()->create(['role_id' => $role->id]);
        $changeRequest = ChangeRequest::factory()->create();
        $response = $this->actingAs($user)->post('/api/v1/approve-request/' . $changeRequest->id);
        $response->assertStatus(Response::HTTP_CREATED)
            ->assertJson([
                "status" => true,
            ]);

    }

    public function testDeclineRequestWithAuth()
    {
        ///  $this->withOutException();

        $role = Role::factory()->create(['name' => 'Super Admin']);
        $user = User::factory()->create(['role_id' => $role->id]);
        $changeRequest = ChangeRequest::factory()->create();
        $response = $this->actingAs($user)->post('/api/v1/decline-request/' . $changeRequest->id);
        $response->assertStatus(Response::HTTP_CREATED)
            ->assertJson([
                "status" => true,
            ]);

    }
}
