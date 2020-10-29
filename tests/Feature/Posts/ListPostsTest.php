<?php

namespace Tests\Feature\Posts;

use App\Models\Post;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ListPostsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function can_list_posts()
    {
        $post = Post::factory()->create();

        $response = $this->getJson(route('api.v1.posts.index'));

        $response->assertJsonFragment([
                'data'=>[
                    [
                        'type' => 'posts',
                        'id' => $post->getRouteKey(),
                        'attributes' => [
                        'title'=>$post->title,
                        'slug' => $post->slug
                        ]
                    ]
                ]
        ]);
    }
}
