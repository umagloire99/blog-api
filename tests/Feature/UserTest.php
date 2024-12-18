<?php

namespace Tests\Feature;

use App\Enums\RoleEnum;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    private $post;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed();
    }

    /**
     * Test admin-only routes.
     */
    public function test_admin_routes()
    {
        $admin = User::whereHasRole(RoleEnum::ADMIN)->first();
        $user = User::factory()->create();

        $this->actingAs($admin);

        $this->get(route('users.index'))->assertStatus(200);
        $this->get(route('users.create'))->assertStatus(200);
        $this->post(route('users.store'), [
            'name' => 'Test User',
            'email' => 'test1@example.com',
            'password' => 'password',
            'role_id' => 1
        ])->assertStatus(201);

        $this->get(route('users.show', $user->username))->assertStatus(200);
        $this->get(route('users.edit', $user->username))->assertStatus(200);
    }
}
