<?php

namespace Tests\Feature\Post;

use App\Http\Requests\Post\PostRequest;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Validator;
use Tests\TestCase;

class PostRequestTest extends TestCase
{
    use RefreshDatabase;

    private function validate(array $data)
    {
        $request = new PostRequest();
        $request->merge($data);

        return Validator::make(
            $data,
            $request->rules(),
            $request->messages()
        );
    }

    public function testContentTypeIdIsRequired()
    {
        $validator = $this->validate([]);

        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('content_type_id', $validator->errors()->toArray());
    }

    public function testTextPostRequiresContent()
    {
        $validator = $this->validate([
            'content_type_id' => 1,
            'content' => null,
        ]);

        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('content', $validator->errors()->toArray());
    }

    public function testQuotePostRequiresContentAndQuoteAuthor()
    {
        $validator = $this->validate([
            'content_type_id' => 2,
            'content' => null,
            'quote_author' => null,
        ]);

        $this->assertTrue($validator->fails());
        $errors = $validator->errors()->toArray();

        $this->assertArrayHasKey('content', $errors);
        $this->assertArrayHasKey('quote_author', $errors);
    }

    public function testImagePostRequiresFileOrUrl()
    {
        $validator = $this->validate([
            'content_type_id' => 3,
            'image_file' => null,
            'image_url' => null,
        ]);

        $this->assertTrue($validator->fails());
        $errors = $validator->errors()->toArray();

        $this->assertArrayHasKey('image_file', $errors);
        $this->assertArrayHasKey('image_url', $errors);
    }

    public function testVideoPostRequiresYoutubeUrl()
    {
        $validator = $this->validate([
            'content_type_id' => 4,
            'video' => null,
        ]);

        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('video', $validator->errors()->toArray());
    }

    public function testLinkPostRequiresLink()
    {
        $validator = $this->validate([
            'content_type_id' => 5,
            'link' => null,
        ]);

        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('link', $validator->errors()->toArray());
    }

    public function testTagsMustBeStringIfPresent()
    {
        $validator = $this->validate([
            'content_type_id' => 1,
            'content' => 'Текст',
            'tags' => ['not', 'string'],
        ]);

        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('tags', $validator->errors()->toArray());
    }

    public function testQuotePostFailsWithoutQuoteAuthor()
    {
        $validator = $this->validate([
            'content_type_id' => 2,
            'content' => 'Цитата',
            'quote_author' => null,
        ]);

        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('quote_author', $validator->errors()->toArray());
    }

    public function testImagePostFailsWithInvalidFile()
    {
        $validator = $this->validate([
            'content_type_id' => 3,
            'image_file' => 'not-an-image',
        ]);

        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('image_file', $validator->errors()->toArray());
    }

    public function testVideoPostFailsWithInvalidUrl()
    {
        $validator = $this->validate([
            'content_type_id' => 4,
            'video' => 'https://example.com/not-youtube',
        ]);

        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('video', $validator->errors()->toArray());
    }
}
