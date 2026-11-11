<?php

namespace Tests\Feature\Search;

use App\Models\User;
use App\Models\Post;
use App\Models\ContentType;
use App\Models\Hashtag;
use App\Models\Comment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SearchControllerTest extends TestCase
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

    public function testSearchFindsUsers()
    {
        $this->actingAs($this->makeUser()->create());

        $user = $this->makeUser()->create(['login' => 'kutin']);

        $response = $this->get('/search?q=kutin');

        $response->assertStatus(200);

        $users = $response->viewData('users');

        $this->assertCount(1, $users);
        $this->assertEquals('kutin', $users[0]->login);
    }

    public function testSearchFindsTagPosts()
    {
        $this->actingAs($this->makeUser()->create());

        $tag = Hashtag::create(['name' => 'php']);

        $post = $this->makePost()->create();
        $post->hashtags()->attach($tag->id);

        $response = $this->get('/search?q=%23php');
        $response->assertStatus(200);

        $tagPosts = $response->viewData('tagPosts');

        $this->assertCount(1, $tagPosts);
        $this->assertEquals($post->id, $tagPosts[0]->id);
    }

    public function testSearchFindsPostsByFulltext()
    {
        $this->actingAs($this->makeUser()->create());

        $post = $this->makePost()->create([
            'title' => 'Laravel framework',
            'content' => 'PHP backend',
        ]);

        $response = $this->get('/search?q=Laravel');

        $posts = $response->viewData('posts');

        $this->assertCount(1, $posts);
        $this->assertEquals($post->id, $posts[0]->id);
    }

    public function testSearchFallbackLikeWhenFulltextEmpty()
    {
        $this->actingAs($this->makeUser()->create());

        $post = $this->makePost()->create([
            'title' => 'Hello world',
            'content' => 'Test content',
        ]);

        $response = $this->get('/search?q=Hello');

        $posts = $response->viewData('posts');

        $this->assertCount(1, $posts);
        $this->assertEquals($post->id, $posts[0]->id);
    }

    public function testSearchFindsPostsByComments()
    {
        $this->actingAs($this->makeUser()->create());

        $post = $this->makePost()->create();

        Comment::create([
            'post_id' => $post->id,
            'author_id' => $this->makeUser()->create()->id,
            'content' => 'Interesting text',
        ]);

        $response = $this->get('/search?q=Interesting');

        $commentPosts = $response->viewData('commentPosts');

        $this->assertCount(1, $commentPosts);
        $this->assertEquals($post->id, $commentPosts[0]->id);
    }

    public function testSearchRemovesDuplicatesBetweenSources()
    {
        $this->actingAs($this->makeUser()->create());

        $tag = Hashtag::create(['name' => 'php']);

        $post = $this->makePost()->create([
            'title' => 'php',
            'content' => 'php',
        ]);

        $post->hashtags()->attach($tag->id);

        $response = $this->get('/search?q=php');

        $tagPosts = $response->viewData('tagPosts');
        $posts = $response->viewData('posts');
        $commentPosts = $response->viewData('commentPosts');

        $this->assertCount(1, $tagPosts);
        $this->assertCount(0, $posts);
        $this->assertCount(0, $commentPosts);
    }

    public function testSearchCleansQueryFromSymbols()
    {
        $this->actingAs($this->makeUser()->create());

        $user = $this->makeUser()->create(['login' => 'kutin']);

        $response = $this->get('/search?q=kutin!!!');

        $users = $response->viewData('users');

        $this->assertCount(1, $users);
        $this->assertEquals('kutin', $users[0]->login);
    }

    public function testSearchRemovesHashForDisplay()
    {
        $this->actingAs($this->makeUser()->create());

        $response = $this->get('/search?q=%23php');

        $response->assertStatus(200);

        $this->assertEquals('php', $response->viewData('query'));
        $this->assertEquals('#php', $response->viewData('rawQuery'));
    }

    public function testSearchReturnsEmptyResults()
    {
        $this->actingAs($this->makeUser()->create());

        $response = $this->get('/search?q=unknown');

        $this->assertCount(0, $response->viewData('users'));
        $this->assertCount(0, $response->viewData('posts'));
        $this->assertCount(0, $response->viewData('tagPosts'));
        $this->assertCount(0, $response->viewData('commentPosts'));
    }

    public function testSearchRequiresQuery()
    {
        $this->actingAs($this->makeUser()->create());

        $response = $this->get('/search?q=');

        $response->assertRedirect();
        $response->assertSessionHasErrors('q');
    }

    public function testGuestCannotAccessSearch()
    {
        $response = $this->get('/search?q=test');

        $response->assertRedirect('/login');
    }
}
