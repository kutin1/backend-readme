<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'author_id',
    'content_type_id',
    'title',
    'content',
    'quote_author',
    'image',
    'video',
    'link',
    'views',
    'is_repost',
    'original_post_id',
])]

class Post extends Model
{
    use HasFactory;

    protected $casts = [
        'is_repost' => 'boolean',
    ];

    // -----------------------------
    // СВЯЗИ
    // -----------------------------

    // Автор поста
    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    // Тип контента (текст, фото, видео)
    public function contentType()
    {
        return $this->belongsTo(ContentType::class);
    }

    // Хештеги (many-to-many)
    public function hashtags()
    {
        return $this->belongsToMany(Hashtag::class, 'post_hashtag');
    }

    // Комментарии к посту
    public function comments()
    {
        return $this->hasMany(Comment::class)->latest();
    }

    // Лайки поста
    public function likes()
    {
        return $this->hasMany(Like::class);
    }

    // Оригинальный пост (если это репост)
    public function originalPost()
    {
        return $this->belongsTo(Post::class, 'original_post_id');
    }

    // Репосты этого поста
    public function reposts()
    {
        return $this->hasMany(Post::class, 'original_post_id');
    }
}
