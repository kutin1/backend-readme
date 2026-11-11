<?php

namespace Tests\Feature\Search;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SearchRequestTest extends TestCase
{
    use RefreshDatabase;

    public function testQueryIsRequired()
    {
        $this->actingAs($this->makeUser()->create());

        $response = $this->get('/search?q=');

        $response->assertRedirect();
        $response->assertSessionHasErrors('q');
    }

    public function testQueryMustBeString()
    {
        $this->actingAs($this->makeUser()->create());

        $response = $this->get('/search?q[]=test');

        $response->assertRedirect();
        $response->assertSessionHasErrors('q');
    }

    public function testQueryIsTrimmed()
    {
        $this->actingAs($this->makeUser()->create());

        $response = $this->get('/search?q=%20php%20');

        $this->assertEquals('php', $response->viewData('query'));
    }
}
