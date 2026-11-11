<?php

namespace Tests\Feature\Post;

use App\Models\Post;
use App\Models\User;
use App\Models\ContentType;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PostShowTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        ContentType::unguard();

        ContentType::create(['id' => 1, 'name' => 'text', 'icon' => 'text']);
        ContentType::create(['id' => 2, 'name' => 'quote', 'icon' => 'quote']);
        ContentType::create(['id' => 3, 'name' => 'photo', 'icon' => 'photo']);
        ContentType::create(['id' => 4, 'name' => 'video', 'icon' => 'video']);
        ContentType::create(['id' => 5, 'name' => 'link', 'icon' => 'link']);
    }

    public function testPostViewsIncrementOnShow()
    {
        $user = $this->makeUser()->create();
        $this->actingAs($user);

        $post = $this->makePost()->create([
            'content_type_id' => 1,
            'views' => 0,
        ]);

        $response = $this->get(route('post.show', $post));

        $response->assertStatus(200);

        $post->refresh();

        $this->assertEquals(1, $post->views);
    }
}
