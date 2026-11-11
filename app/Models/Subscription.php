<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['subscriber_id', 'target_id'])]
class Subscription extends Model
{
    use HasFactory;

    // -----------------------------
    // СВЯЗИ
    // -----------------------------

    // Кто подписался
    public function subscriber()
    {
        return $this->belongsTo(User::class, 'subscriber_id');
    }

    // На кого подписались
    public function target()
    {
        return $this->belongsTo(User::class, 'target_id');
    }
}
