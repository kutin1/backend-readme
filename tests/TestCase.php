<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    public function makePost()
    {
        return \Database\Factories\Test\PostFactory::new();
    }
    public function makeUser()
    {
        return \Database\Factories\Test\UserFactory::new();
    }
    public function makeLike()
    {
        return \Database\Factories\Test\LikeFactory::new();
    }
    public function makeComment()
    {
        return \Database\Factories\Test\CommentFactory::new();
    }
    public function makeSubscription()
    {
        return \Database\Factories\Test\SubscriptionFactory::new();
    }
    public function makeMessage()
    {
        return \Database\Factories\Test\MessageFactory::new();
    }
}
