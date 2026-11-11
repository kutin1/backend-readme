<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['author_id', 'post_id', 'content'])]
class Comment extends Model
{
    use HasFactory;

    // -----------------------------
    // СВЯЗИ
    // -----------------------------

    // Автор комментария
    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    // Пост, к которому относится комментарий
    public function post()
    {
        return $this->belongsTo(Post::class);
    }
}
