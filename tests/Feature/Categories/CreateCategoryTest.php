<?php

namespace Tests\Feature\Categories;

use App\Models\Category;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class CreateCategoryTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function register_user_can_create_categories()
    {
        $user = User::factory()->create();
        $category = Category::factory()->raw();

        Sanctum::actingAs($user);

        $this->jsonApi()->withData([
            'type' => 'categories',
            'attributes' => $category,
        ])->post(route('api.v1.categories.create'))
            ->assertCreated();;

        $this->assertDatabaseHas('categories',[
            'name' => $category['name'],
            'slug' => $category['slug'],
        ]);

    }

}
