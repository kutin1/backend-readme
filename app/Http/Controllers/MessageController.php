<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Http\Requests\MessageRequest;
use App\Services\MessageService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\JsonResponse;

class MessageController extends Controller
{
    public function __construct(
        private readonly MessageService $messageService
    ) {
    }

    // список контактов + первый чат
    public function index()
    {
        $auth = Auth::user();

        // все пользователи, с которыми был диалог
        $contacts = $this->messageService->contacts($auth->id);

        // если контактов нет — просто показываем пустую страницу
        if ($contacts->isEmpty()) {
            return view('messages.index', [
                'contacts' => [],
                'activeUser' => null,
                'messages' => [],
            ]);
        }

        // первый контакт — активный
        $activeUser = $contacts->first();

        // диалог с ним
        $messages = $this->messageService->dialog($auth->id, $activeUser->id);

        return view('messages.index', compact('contacts', 'activeUser', 'messages'));
    }

    // открыть чат с конкретным пользователем
    public function show(User $user)
    {
        $auth = Auth::user();

        $contacts = $this->messageService->contacts($auth->id);

        // Если контакта нет в списке — добавляем временно
        if (!$contacts->contains('id', $user->id)) {
            $contacts->prepend($user);
        }

        $this->messageService->markAsRead($auth->id, $user->id);

        $messages = $this->messageService->dialog($auth->id, $user->id);

        return view('messages.index', [
            'contacts' => $contacts,
            'activeUser' => $user,
            'messages' => $messages,
        ]);
    }

    // отправить сообщение
    public function store(MessageRequest $request, User $user)
    {

        $this->messageService->send(
            Auth::user(),
            $user->id,
            $request->content
        );

        return redirect()->route('messages.show', $user);
    }
}
