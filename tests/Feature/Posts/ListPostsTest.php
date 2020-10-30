<?php

namespace Tests\Feature\Posts;

use App\Models\Post;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ListPostsTest extends TestCase
{

    use RefreshDatabase;

    /** @test */
    public function can_fetch_single_posts()
    {
        $post = Post::factory()->create();
        $response = $this->jsonApi()->get(route('api.v1.posts.read', $post));
        $response->assertJson([
            'data' => [
                'type' => 'posts',
                'id' => (string)$post->getRouteKey(),
                'attributes' => [
                    'title' => $post->title,
                    'slug' => $post->slug,
                    'excerpt' => $post->excerpt,
                    'publishedAt' => $post->published_at->toAtomString(),
                    'createdAt' => $post->created_at->toAtomString(),
                    'updatedAt' => $post->updated_at->toAtomString(),
                ],
                'links' => [
                    'self' => route('api.v1.posts.read', $post)
                ]
            ]
        ]);
//        $this->assertNull(
//            $response->json('data.relationships.authors.data'),
//            "The key 'data.relationships.authors.data' must be null"
//        );
    }

    /** @test */
    public function can_fetch_all_posts()
    {
        $post = Post::factory()->times(3)->create();
        $response = $this->jsonApi()->get(route('api.v1.posts.index'));
        $response->assertJson([
            'data' => [
                [
                    'type' => 'posts',
                    'id' => (string)$post[0]->getRouteKey(),
                    'attributes' => [
                        'title' => $post[0]->title,
                        'slug' => $post[0]->slug,
                        'excerpt' => $post[0]->excerpt,
                        'publishedAt' => $post[0]->published_at->toAtomString(),
                        'createdAt' => $post[0]->created_at->toAtomString(),
                        'updatedAt' => $post[0]->updated_at->toAtomString(),
                    ],
                    'links' => [
                        'self' => route('api.v1.posts.read', $post[0])
                    ]
                ],
                [
                    'type' => 'posts',
                    'id' => (string)$post[1]->getRouteKey(),
                    'attributes' => [
                        'title' => $post[1]->title,
                        'slug' => $post[1]->slug,
                        'excerpt' => $post[1]->excerpt,
                        'publishedAt' => $post[1]->published_at->toAtomString(),
                        'createdAt' => $post[1]->created_at->toAtomString(),
                        'updatedAt' => $post[1]->updated_at->toAtomString(),
                    ],
                    'links' => [
                        'self' => route('api.v1.posts.read', $post[1])
                    ]
                ],
                [
                    'type' => 'posts',
                    'id' => (string)$post[2]->getRouteKey(),
                    'attributes' => [
                        'title' => $post[2]->title,
                        'slug' => $post[2]->slug,
                        'excerpt' => $post[2]->excerpt,
                        'publishedAt' => $post[2]->published_at->toAtomString(),
                        'createdAt' => $post[2]->created_at->toAtomString(),
                        'updatedAt' => $post[2]->updated_at->toAtomString(),
                    ],
                    'links' => [
                        'self' => route('api.v1.posts.read', $post[2])
                    ]
                ],
            ]
        ]);
    }
}
