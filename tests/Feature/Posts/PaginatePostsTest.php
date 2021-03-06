<?php

namespace Tests\Feature\Posts;

use App\Models\Post;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PaginatePostsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function testExample()
    {
        Post::factory()->times(10)->create();
        $url= route('api.v1.posts.index',['page[size]'=>2,'page[number]'=>3]);

        $response = $this->jsonApi()->get($url);

        $response->assertJsonStructure([
            'links' => ['first', 'last', 'prev', 'next']
        ]);
        $response->assertJsonFragment([
            'first' => route('api.v1.posts.index', ['page[number]' => 1, 'page[size]' => 2]),
            'last' => route('api.v1.posts.index', ['page[number]' => 5, 'page[size]' => 2]),
            'prev' => route('api.v1.posts.index', ['page[number]' => 2, 'page[size]' => 2]),
            'next' => route('api.v1.posts.index', ['page[number]' => 4, 'page[size]' => 2]),
        ]);
    }
}
