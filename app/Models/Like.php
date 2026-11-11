<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['user_id', 'post_id'])]
class Like extends Model
{
    use HasFactory;

    public $timestamps = false;

    // -----------------------------
    // СВЯЗИ
    // -----------------------------

    // Кто поставил лайк
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Какой пост лайкнули
    public function post()
    {
        return $this->belongsTo(Post::class);
    }
}
