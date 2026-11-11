<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Like;

class LikeSeeder extends Seeder
{
    public function run(): void
    {
        Like::factory()->count(300)->create();
    }
}
