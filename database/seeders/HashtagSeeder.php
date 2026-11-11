<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Hashtag;

class HashtagSeeder extends Seeder
{
    public function run(): void
    {
        $faker = fake('ru_RU');

        for ($i = 0; $i < 40; $i++) {

            do {
                $word = explode(' ', $faker->realText(20))[0];
                $word = preg_replace('/[^а-яА-ЯёЁ0-9]/u', '', $word);
                $tag = mb_strtolower($word);
            } while (Hashtag::where('name', $tag)->exists());

            Hashtag::create([
                'name' => $tag,
            ]);
        }
    }
}
