<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

#[Fillable(['email', 'login', 'password', 'avatar', 'registered_at'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasApiTokens;
    use HasFactory;
    use Notifiable;

    protected function casts(): array
    {
        return [
            'registered_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // -----------------------------
    // СВЯЗИ
    // -----------------------------

    // Посты пользователя
    public function posts()
    {
        return $this->hasMany(Post::class, 'author_id');
    }

    // Комментарии пользователя
    public function comments()
    {
        return $this->hasMany(Comment::class, 'author_id');
    }

    // Лайки пользователя
    public function likes()
    {
        return $this->hasMany(Like::class);
    }

    // На кого я подписан
    public function subscriptions()
    {
        return $this->hasMany(Subscription::class, 'subscriber_id');
    }

    // Кто подписан на меня
    public function subscribers()
    {
        return $this->hasMany(Subscription::class, 'target_id');
    }

    // Отправленные сообщения
    public function sentMessages()
    {
        return $this->hasMany(Message::class, 'sender_id');
    }

    // Полученные сообщения
    public function receivedMessages()
    {
        return $this->hasMany(Message::class, 'receiver_id');
    }

    public function likedPosts()
    {
        return $this->belongsToMany(Post::class, 'likes', 'user_id', 'post_id')
            ->with([
                'author',
                'hashtags',
                'contentType',
                'originalPost',
                'originalPost.author',
                'originalPost.hashtags'
            ]);
    }

    public function getYearsOnSiteAttribute(): int
    {
        return now()->diffInYears($this->created_at);
    }

    public function getYearsOnSiteTextAttribute(): string
    {
        $n = $this->years_on_site;

        $forms = ['год', 'года', 'лет'];

        $nMod100 = $n % 100;
        if ($nMod100 >= 11 && $nMod100 <= 19) {
            return $forms[2];
        }

        $nMod10 = $n % 10;
        if ($nMod10 === 1) {
            return $forms[0];
        }
        if ($nMod10 >= 2 && $nMod10 <= 4) {
            return $forms[1];
        }

        return $forms[2];
    }
}
