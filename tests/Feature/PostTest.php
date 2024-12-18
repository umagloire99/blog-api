<?php

namespace Tests\Feature;

use App\Enums\RoleEnum;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PostTest extends TestCase
{
    use RefreshDatabase;

    private $post;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed();
        $this->post = Post::factory()->create();
    }

    /**
     * A basic feature test example.
     */
    public function test_unauthenticated_routes(): void
    {

        $this->get(route('posts.index'))->assertStatus(200);
        $this->get(route('posts.show', $this->post->slug))->assertStatus(200);
        $this->get(route('posts.comments', $this->post->slug))->assertStatus(200);
    }

    /**
     * Test Policy apply on posts authenticated routes
     *
     */
    public function test_access_denied_routes()
    {
        $user = User::whereHasRole(RoleEnum::USER->value)->first();

        $this->actingAs($user);

         // Posts
         $this->post(route('posts.store'), [
            'title' => 'New Post',
            'content' => 'This is a new post.',
        ])->assertStatus(403);

        $this->patch(route('posts.update', $this->post->slug), [
            'title' => 'Updated Post',
            'content' => 'This is a new post.',
        ])->assertStatus(403);

        $this->delete(route('posts.destroy', $this->post->slug))->assertStatus(403);
    }

    /**
     * test_successfull_access_routes
     *
     */
    public function test_successfull_access_routes()
    {
        $user = User::whereHasRole(RoleEnum::ADMIN->value)->first();

        $this->actingAs($user);

         // Posts
         $this->post(route('posts.store'), [
            'title' => 'New Post',
            'content' => 'This is a new post.',
        ])->assertStatus(201);

        $this->patch(route('posts.update', $this->post->slug), [
            'title' => $this->post->title,
            'content' => 'This is a new post.',
        ])->assertStatus(200);

        // Comments
        $this->post(route('posts.comments.add', $this->post->slug), [
            'content' => 'New comment.',
        ])->assertStatus(200);

        $this->delete(route('posts.destroy', $this->post->slug))->assertStatus(200);
    }
}
