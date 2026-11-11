<?php

namespace App\Services;

use App\Models\Message;
use App\Models\User;

class MessageService
{
    public function send(User $sender, int $receiverId, string $content): Message
    {
        $receiver = User::findOrFail($receiverId);

        return Message::create([
            'sender_id' => $sender->id,
            'receiver_id' => $receiver->id,
            'content' => trim($content),
        ]);
    }

    // Получить список контактов
    public function contacts(int $userId)
    {
        return User::whereIn('id', function ($q) use ($userId) {
                $q->select('sender_id')
                  ->from('messages')
                  ->where('receiver_id', $userId);
        })
            ->orWhereIn('id', function ($q) use ($userId) {
                $q->select('receiver_id')
                  ->from('messages')
                  ->where('sender_id', $userId);
            })
            ->where('id', '!=', $userId)
            ->distinct()
            ->get();
    }

    // Получить диалог между двумя пользователями
    public function dialog(int $userId, int $otherId)
    {
        return Message::where(function ($q) use ($userId, $otherId) {
                $q->where('sender_id', $userId)
                  ->where('receiver_id', $otherId);
        })
            ->orWhere(function ($q) use ($userId, $otherId) {
                $q->where('sender_id', $otherId)
                  ->where('receiver_id', $userId);
            })
            ->orderBy('created_at')
            ->get();
    }

    public function lastMessageBetween(int $userId, int $otherId)
    {
        return Message::where(function ($q) use ($userId, $otherId) {
                $q->where('sender_id', $userId)
                  ->where('receiver_id', $otherId);
        })
            ->orWhere(function ($q) use ($userId, $otherId) {
                $q->where('sender_id', $otherId)
                  ->where('receiver_id', $userId);
            })
            ->latest()
            ->first();
    }

    public function markAsRead(int $authId, int $contactId)
    {
        Message::where('sender_id', $contactId)
            ->where('receiver_id', $authId)
            ->whereNull('read_at')
            ->update(['read_at' => now()]);
    }

    public function unreadCount(int $authId, int $contactId): int
    {
        return Message::where('sender_id', $contactId)
            ->where('receiver_id', $authId)
            ->whereNull('read_at')
            ->count();
    }

    public function unreadCountTotal(int $authId): int
    {
        return Message::where('receiver_id', $authId)
            ->whereNull('read_at')
            ->count();
    }
}
