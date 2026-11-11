<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['name'])]
class Hashtag extends Model
{
    use HasFactory;

    // -----------------------------
    // СВЯЗИ
    // -----------------------------

    // Посты, в которых используется хештег
    public function posts()
    {
        return $this->belongsToMany(Post::class, 'post_hashtag');
    }
}
