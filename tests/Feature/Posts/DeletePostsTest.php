<?php

namespace Tests\Feature\Posts;

use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class DeletePostsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function guest_users_cannot_delete_posts()
    {
        $post = Post::factory()->create();

        $this->jsonApi()
            ->delete(route('api.v1.posts.delete',$post))
            ->assertStatus(Response::HTTP_UNAUTHORIZED); //401
    }

    /** @test */
    public function authenticated_users_can_delete_their_posts()
    {
        $post = Post::factory()->create();

        Sanctum::actingAs($post->user);

        $this->jsonApi()
            ->delete(route('api.v1.posts.delete',$post))
            ->assertStatus(Response::HTTP_NO_CONTENT); //204
    }

    /** @test */
    public function authenticated_users_cannot_delete_other_posts()
    {
        $post = Post::factory()->create();

        Sanctum::actingAs(User::factory()->create());

        $this->jsonApi()
            ->delete(route('api.v1.posts.delete',$post))
            ->assertStatus(Response::HTTP_FORBIDDEN); //403
    }

}
