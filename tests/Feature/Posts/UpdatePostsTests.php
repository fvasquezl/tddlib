<?php

namespace Tests\Feature\Posts;

use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class UpdatePostsTests extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function guest_users_cannot_update_posts()
    {
        $post = Post::factory()->create();

        $this->jsonApi()
            ->patch(route('api.v1.posts.update',$post))
            ->assertStatus(401);

    }

    /** @test */
    public function registered_users_can_update_their_posts()
    {

        $post = Post::factory()->create();

        Sanctum::actingAs($post->user);

        $this->jsonApi()->withData([
            'type'=>'posts',
            'id'=> $post->getRouteKey(),
            'attributes'=>[
                'title' => 'Title changed',
                'slug' => 'title-changed',
                'excerpt' => 'Excerpt changed',
                'published_at' => now()
            ]
        ])
            ->patch(route('api.v1.posts.update',$post))
            ->assertStatus(Response::HTTP_OK); //200

        $this->assertDatabaseHas('posts',[
            'title' => 'Title changed',
            'slug' => 'title-changed',
            'excerpt' => 'Excerpt changed',
            'published_at' => now()
        ]);
    }

    /** @test */
    public function registered_users_cannot_update_other_posts()
    {
        $post = Post::factory()->create();

        Sanctum::actingAs(User::factory()->create());

        $this->jsonApi()->withData([
            'type'=>'posts',
            'id'=> $post->getRouteKey(),
            'attributes'=>[
                'title' => 'Title changed',
                'slug' => 'title-changed',
                'excerpt' => 'Excerpt changed',
                'published_at' => now()
            ]
        ])->patch(route('api.v1.posts.update',$post))
            ->assertStatus(Response::HTTP_FORBIDDEN); //403

        $this->assertDatabaseMissing('posts',[
            'title' => 'Title changed',
            'slug' => 'title-changed',
            'excerpt' => 'Excerpt changed',
            'published_at' => now()
        ]);
    }

    /** @test */
    public function can_update_only_title()
    {
        $post = Post::factory()->create();
        Sanctum::actingAs($post->user);

        $this->jsonApi()
            ->withData([
            'type'=>'posts',
            'id'=> $post->getRouteKey(),
            'attributes'=>[
                'title' => 'Title changed',
            ]
        ])->patch(route('api.v1.posts.update',$post))
            ->assertStatus(Response::HTTP_OK); //200

        $this->assertDatabaseHas('posts',[
            'title' => 'Title changed',
        ]);
    }

    /** @test */
    public function can_update_only_slug()
    {
        $post = Post::factory()->create();
        Sanctum::actingAs($post->user);

        $this->jsonApi()
            ->withData([
                'type'=>'posts',
                'id'=> $post->getRouteKey(),
                'attributes'=>[
                    'slug' => 'slug-changed',
                ]
            ])->patch(route('api.v1.posts.update',$post))
            ->assertStatus(Response::HTTP_OK); //200

        $this->assertDatabaseHas('posts',[
            'slug' => 'slug-changed',
        ]);
    }


    /** @test */
    public function can_update_only_excerpt()
    {
        $post = Post::factory()->create();
        Sanctum::actingAs($post->user);

        $this->jsonApi()
            ->withData([
                'type'=>'posts',
                'id'=> $post->getRouteKey(),
                'attributes'=>[
                    'excerpt' => 'excerpt changed',
                ]
            ])->patch(route('api.v1.posts.update',$post))
            ->assertStatus(Response::HTTP_OK); //200

        $this->assertDatabaseHas('posts',[
            'excerpt' => 'excerpt changed',
        ]);
    }
}
