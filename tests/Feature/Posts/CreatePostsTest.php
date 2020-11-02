<?php

namespace Tests\Feature\Posts;

use App\Models\Category;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class CreatePostsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function register_user_can_create_a_post()
    {
        $user = User::factory()->create();
        $category = Category::factory()->create();;

        $post = array_filter(Post::factory()->raw([
            'category_id'=>null,
            'published_at' => now()
        ]));

        Sanctum::actingAs($user);

        $this->jsonApi()->withData([
            'type' => 'posts',
            'attributes' => $post,
            'relationships' => [
                'categories' => [
                    'data' => [
                        'id' => $category->getRouteKey(),
                        'type' => 'categories'
                    ]
                ]
            ]
        ])->post(route('api.v1.posts.create'))
            ->assertCreated();

        $this->assertDatabaseHas('posts', [
            'user_id' => $user->id,
            'title' => $post['title'],
            'slug' => $post['slug'],
            'excerpt' => $post['excerpt'],
        ]);

    }

    /**  @test */
    public function categories_is_required()
    {
        $post = Post::factory()->raw(['category_id' => null]);
        Sanctum::actingAs(User::factory()->create());
        $this->jsonApi()->withData([
            'type' => 'posts',//
            'attributes' => $post
        ])->post(route('api.v1.posts.create'))
            ->assertStatus(422)
            ->assertJsonFragment(['source' => ['pointer' => '/data']]);
        $this->assertDatabaseMissing('posts', $post);
    }

    /**  @test */
    public function categories_must_be_a_relationship_object()
    {
        $post = Post::factory()->raw(['category_id' => null]);
        $post['categories'] = 'slug';

        Sanctum::actingAs(User::factory()->create());

        $this->jsonApi()->withData([
            'type' => 'posts',
            'attributes' => $post
        ])->post(route('api.v1.posts.create'))
            ->assertStatus(422)
            ->assertSee('data\/attributes\/categories');
        ;
        $this->assertDatabaseMissing('posts', $post);
    }

    /**  @test */
    public function title_is_required()
    {
        $post = Post::factory()->raw(['title' => '']);
        Sanctum::actingAs(User::factory()->create());
        $this->jsonApi()->withData([
            'type' => 'posts',
            'attributes' => $post
        ])->post(route('api.v1.posts.create'))
            ->assertStatus(422)
            ->assertSee('data\/attributes\/title');

        $this->assertDatabaseMissing('posts', $post);
    }

    /**  @test */
    public function excerpt_is_required()
    {
        $post = Post::factory()->raw(['excerpt' => '']);
        Sanctum::actingAs(User::factory()->create());
        $this->jsonApi()->withData([
            'type' => 'posts',
            'attributes' => $post
        ])->post(route('api.v1.posts.create'))
            ->assertStatus(422)
            ->assertSee('data\/attributes\/excerpt');

        $this->assertDatabaseMissing('posts', $post);
    }

    /**  @test */
    public function slug_is_required()
    {
        $post = Post::factory()->raw(['slug' => '']);
        Sanctum::actingAs(User::factory()->create());
        $this->jsonApi()->withData([
            'type' => 'posts',
            'attributes' => $post
        ])->post(route('api.v1.posts.create'))
            ->assertStatus(422)
            ->assertSee('data\/attributes\/slug');

        $this->assertDatabaseMissing('posts', $post);
    }

}
