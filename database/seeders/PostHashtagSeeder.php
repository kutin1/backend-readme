<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Post;
use App\Models\Hashtag;

class PostHashtagSeeder extends Seeder
{
    public function run(): void
    {
        $hashtags = Hashtag::all();

        Post::all()->each(function ($post) use ($hashtags) {
            $randomTags = $hashtags->random(rand(1, 5))->pluck('id');
            $post->hashtags()->syncWithoutDetaching($randomTags);
        });
    }
}
