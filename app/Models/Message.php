<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['sender_id', 'receiver_id', 'content'])]
class Message extends Model
{
    use HasFactory;

    // -----------------------------
    // СВЯЗИ
    // -----------------------------

    // Отправитель сообщения
    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    // Получатель сообщения
    public function receiver()
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }
}
