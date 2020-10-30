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
        $category = Category::factory()->create();
        dd($category);

        $post = array_filter(Post::factory()->raw());

        Sanctum::actingAs($user);

        $this->jsonApi()->withData([
            'type' => 'posts',
            'attributes' => $post,
            'relationships' => [
                'categories' => [
                    'data' => [
                        'id' =>$category->getRouteKey(),
                        'type' => 'categories'
                    ]
                ]
            ]
        ])->post(route('api.v1.posts.create'))->dump()
            ->assertCreated();

        $this->assertDatabaseHas('posts',[
            'user_id' => $user->id,
            'title' => $post['title'],
            'slug' => $post['slug'],
            'excerpt' => $post['excerpt'],
        ]);

    }
}
