<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ContentType;

class ContentTypeSeeder extends Seeder
{
    public function run(): void
    {
        $types = [
            ['name' => 'Текст', 'icon' => 'text'],
            ['name' => 'Цитата', 'icon' => 'quote'],
            ['name' => 'Картинка', 'icon' => 'photo'],
            ['name' => 'Видео', 'icon' => 'video'],
            ['name' => 'Ссылка', 'icon' => 'link'],
        ];

        foreach ($types as $type) {
            ContentType::create($type);
        }
    }
}
