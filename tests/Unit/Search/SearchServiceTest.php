<?php

namespace Tests\Unit\Search;

use App\Models\User;
use App\Models\Post;
use App\Models\ContentType;
use App\Models\Hashtag;
use App\Models\Comment;
use App\Services\SearchService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SearchServiceTest extends TestCase
{
    use RefreshDatabase;

    private SearchService $service;

    protected function setUp(): void
    {
        parent::setUp();

        ContentType::unguard();

        ContentType::create(['id' => 1, 'name' => 'text', 'icon' => 'text']);
        ContentType::create(['id' => 2, 'name' => 'quote', 'icon' => 'quote']);
        ContentType::create(['id' => 3, 'name' => 'photo', 'icon' => 'photo']);
        ContentType::create(['id' => 4, 'name' => 'video', 'icon' => 'video']);
        ContentType::create(['id' => 5, 'name' => 'link', 'icon' => 'link']);

        $this->service = new SearchService();
    }

    public function testSearchUsers()
    {
        $user = $this->makeUser()->create(['login' => 'kutin']);

        $result = $this->service->search('kutin');

        $this->assertCount(1, $result['users']);
    }

    public function testSearchTags()
    {
        $tag = Hashtag::create(['name' => 'php']);
        $post = $this->makePost()->create();
        $post->hashtags()->attach($tag->id);

        $result = $this->service->search('#php');

        $this->assertCount(1, $result['tagPosts']);
    }

    public function testSearchFulltext()
    {
        $post = $this->makePost()->create([
            'title' => 'Laravel',
            'content' => 'PHP',
        ]);

        $result = $this->service->search('Laravel');

        $this->assertCount(1, $result['posts']);
    }

    public function testSearchFallbackLike()
    {
        $post = $this->makePost()->create([
            'title' => 'Hello world',
        ]);

        $result = $this->service->search('Hello');

        $this->assertCount(1, $result['posts']);
    }

    public function testSearchComments()
    {
        $post = $this->makePost()->create();

        Comment::create([
            'post_id' => $post->id,
            'author_id' => $this->makeUser()->create()->id,
            'content' => 'Interesting',
        ]);

        $result = $this->service->search('Interesting');

        $this->assertCount(1, $result['commentPosts']);
    }

    public function testSearchRemovesDuplicates()
    {
        $tag = Hashtag::create(['name' => 'php']);
        $post = $this->makePost()->create(['title' => 'php']);
        $post->hashtags()->attach($tag->id);

        $result = $this->service->search('php');

        $this->assertCount(1, $result['tagPosts']);
        $this->assertCount(0, $result['posts']);
        $this->assertCount(0, $result['commentPosts']);
    }
}
