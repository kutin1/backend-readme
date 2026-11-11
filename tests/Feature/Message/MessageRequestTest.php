<?php

namespace Tests\Feature\Message;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MessageRequestTest extends TestCase
{
    use RefreshDatabase;

    public function testReceiverIdIsRequired()
    {
        $sender = $this->makeUser()->create();
        $receiver = $this->makeUser()->create();

        $this->actingAs($sender);

        $response = $this->post(route('messages.store', $receiver), [
            'content' => 'Hello',
        ]);

        $response->assertSessionHasErrors('receiver_id');
    }

    public function testReceiverIdMustExist()
    {
        $sender = $this->makeUser()->create();

        $this->actingAs($sender);

        $response = $this->post(route('messages.store', 999), [
            'receiver_id' => 999,
            'content' => 'Hello',
        ]);

        $response->assertStatus(404);
    }

    public function testUserCannotSendMessageToSelf()
    {
        $user = $this->makeUser()->create();

        $this->actingAs($user);

        $response = $this->post(route('messages.store', $user), [
            'receiver_id' => $user->id,
            'content' => 'Self message',
        ]);

        $response->assertSessionHasErrors('receiver_id');
    }

    public function testContentIsRequired()
    {
        $sender = $this->makeUser()->create();
        $receiver = $this->makeUser()->create();

        $this->actingAs($sender);

        $response = $this->post(route('messages.store', $receiver), [
            'receiver_id' => $receiver->id,
            'content' => '',
        ]);

        $response->assertSessionHasErrors('content');
    }

    public function testContentMustBeStringAndMin1()
    {
        $sender = $this->makeUser()->create();
        $receiver = $this->makeUser()->create();

        $this->actingAs($sender);

        // content не строка
        $response = $this->post(route('messages.store', $receiver), [
            'receiver_id' => $receiver->id,
            'content' => ['not', 'string'],
        ]);

        $response->assertSessionHasErrors('content');

        // content слишком короткий
        $response = $this->post(route('messages.store', $receiver), [
            'receiver_id' => $receiver->id,
            'content' => '',
        ]);

        $response->assertSessionHasErrors('content');
    }

    public function testSenderIdIsAutoInjectedByPrepareForValidation()
    {
        $sender = $this->makeUser()->create();
        $receiver = $this->makeUser()->create();

        $this->actingAs($sender);

        $response = $this->post(route('messages.store', $receiver), [
            'receiver_id' => $receiver->id,
            'content' => 'Hello',
        ]);

        $this->assertDatabaseHas('messages', [
            'sender_id' => $sender->id,
            'receiver_id' => $receiver->id,
            'content' => 'Hello',
        ]);
    }
}
