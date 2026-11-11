@extends('layouts.app')

@section('content')

<main class="page__main page__main--messages">
    <h1 class="visually-hidden">Личные сообщения</h1>

    <section class="messages tabs">
        <h2 class="visually-hidden">Сообщения</h2>

        {{-- ========================= --}}
        {{-- ЛЕВАЯ КОЛОНКА — КОНТАКТЫ --}}
        {{-- ========================= --}}
        <div class="messages__contacts">
            <ul class="messages__contacts-list tabs__list">

                @forelse($contacts as $contact)
                    @php
                        $unread = app(\App\Services\MessageService::class)
                            ->unreadCount(auth()->id(), $contact->id);
                    @endphp
                    <li class="messages__contacts-item
                        {{ $activeUser && $activeUser->id === $contact->id ? 'messages__contacts-item--active' : '' }}
                        {{ $unread > 0 ? 'messages__contacts-item--new' : '' }}">
                        <a class="messages__contacts-tab tabs__item {{ $activeUser && $activeUser->id === $contact->id ? 'messages__contacts-tab--active tabs__item--active' : '' }}"
                           href="{{ route('messages.show', $contact) }}">

                            <div class="messages__avatar-wrapper">
                                <x-avatar :src="$contact->avatar"
                                    class="messages__avatar"
                                    alt="Аватар {{ $contact->login }}" />

                                @php
                                    $unread = app(\App\Services\MessageService::class)
                                        ->unreadCount(auth()->id(), $contact->id);
                                @endphp

                                @if($unread > 0)
                                    <i class="messages__indicator">{{ $unread }}</i>
                                @endif

                            </div>

                            <div class="messages__info">
                                <span class="messages__contact-name">
                                    {{ $contact->login }}
                                </span>

                                @php
                                    $last = app(\App\Services\MessageService::class)
                                        ->lastMessageBetween(auth()->id(), $contact->id);
                                @endphp

                                <div class="messages__preview">
                                    <p class="messages__preview-text">
                                        @if($last)
                                            @if($last->sender_id === auth()->id())
                                                Вы: {{ Str::limit($last->content, 25) }}
                                            @else
                                                {{ Str::limit($last->content, 25) }}
                                            @endif
                                        @else
                                            Нет сообщений
                                        @endif
                                    </p>

                                    @if($last)
                                        <time class="messages__preview-time">
                                            {{ $last->created_at->format('H:i') }}
                                        </time>
                                    @endif
                                </div>
                            </div>
                        </a>
                    </li>
                @empty
                    <li class="messages__contacts-item">
                        <span class="messages__contact-name">Нет диалогов</span>
                    </li>
                @endforelse

            </ul>
        </div>

        {{-- ========================= --}}
        {{-- ПРАВАЯ КОЛОНКА — ЧАТ --}}
        {{-- ========================= --}}
        <div class="messages__chat">
            <div class="messages__chat-wrapper">

                <ul class="messages__list tabs__content tabs__content--active">

                    @forelse($messages as $message)
                        <li class="messages__item {{ $message->sender_id === auth()->id() ? 'messages__item--my' : '' }}">

                            <div class="messages__info-wrapper">
                                <div class="messages__item-avatar">
                                    <a class="messages__author-link"
                                       href="{{ route('profile.posts', $message->sender_id) }}">
                                        <x-avatar :src="$message->sender->avatar"
                                            class="messages__avatar"
                                            alt="Аватар {{ $message->sender->login }}" />
                                    </a>
                                </div>

                                <div class="messages__item-info">
                                    <a class="messages__author"
                                       href="{{ route('profile.posts', $message->sender_id) }}">
                                        {{ $message->sender->login }}
                                    </a>

                                    <time class="messages__time">
                                        {{ $message->created_at->diffForHumans() }}
                                    </time>
                                </div>
                            </div>

                            <p class="messages__text">
                                {{ $message->content }}
                            </p>
                        </li>
                    @empty
                        <li class="messages__item">
                            <p class="messages__text">Сообщений пока нет</p>
                        </li>
                    @endforelse

                </ul>

            </div>

            {{-- ========================= --}}
            {{-- ФОРМА ОТПРАВКИ --}}
            {{-- ========================= --}}
            @if($activeUser)
                <div class="comments">
                    <form class="comments__form form"
                          action="{{ route('messages.store', $activeUser) }}"
                          method="POST">
                        @csrf
                        <input type="hidden" name="receiver_id" value="{{ $activeUser->id }}">

                        <div class="comments__my-avatar">
                            <x-avatar :src="auth()->user()->avatar"
                                class="comments__picture"
                                alt="Аватар" />
                        </div>

                        <textarea name="content"
                                  class="comments__textarea form__textarea"
                                  placeholder="Ваше сообщение"></textarea>

                        <button class="comments__submit button button--green" type="submit">
                            Отправить
                        </button>
                    </form>
                </div>
            @endif

        </div>
    </section>
</main>
@endsection
