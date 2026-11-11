<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['name', 'icon'])]
class ContentType extends Model
{
    use HasFactory;

    // -----------------------------
    // СВЯЗИ
    // -----------------------------

    // Посты этого типа контента
    public function posts()
    {
        return $this->hasMany(Post::class);
    }
}
