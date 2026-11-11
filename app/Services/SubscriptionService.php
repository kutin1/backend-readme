<?php

namespace App\Services;

use App\Models\Subscription;
use App\Models\User;

class SubscriptionService
{
    public function subscribe(User $subscriber, int $targetId): void
    {
        $target = User::findOrFail($targetId);

        Subscription::firstOrCreate([
            'subscriber_id' => $subscriber->id,
            'target_id' => $target->id,
        ]);

        // Уведомления будут позже (Jobs)
    }

    public function unsubscribe(User $subscriber, int $targetId): void
    {
        Subscription::where('subscriber_id', $subscriber->id)
            ->where('target_id', $targetId)
            ->delete();
    }
}
