<?php

namespace Database\Factories\Test;

use App\Models\Subscription;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class SubscriptionFactory extends Factory
{
    protected $model = Subscription::class;

    public function definition(): array
    {
        return [
            'subscriber_id' => User::factory(),
            'target_id' => User::factory(),
        ];
    }
}
