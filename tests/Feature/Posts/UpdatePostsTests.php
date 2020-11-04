<?php

namespace Tests\Feature\Posts;

use App\Models\Post;
use Illuminate\Foundation\Testing\RefreshDatabase;
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
}
