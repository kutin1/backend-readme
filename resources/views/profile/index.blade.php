@extends('layouts.app')

@section('content')
    <main class="page__main page__main--profile">
      <h1 class="visually-hidden">Профиль</h1>
    <div class="profile profile--default">
        <div class="profile__user-wrapper">
            <div class="profile__user user container">

                <div class="profile__user-info user__info">
                    <div class="profile__avatar user__avatar">
                        <x-avatar :src="$user->avatar"
                            class="profile__picture user__picture"
                            alt="Аватар пользователя" />
                    </div>
                    <div class="profile__name-wrapper user__name-wrapper">
                        <span class="profile__name user__name">
                            {{ $user->login }}
                        </span>
                        <time class="profile__user-time user__time" datetime="{{ $user->created_at->toDateString() }}">
                            {{ $user->years_on_site }} {{ $user->years_on_site_text }} на сайте
                        </time>
                    </div>
                </div>

                <div class="profile__rating user__rating">
                    <p class="profile__rating-item user__rating-item user__rating-item--publications">
                        <span class="user__rating-amount">{{ $posts->count() }}</span>
                        <span class="profile__rating-text user__rating-text">публикаций</span>
                    </p>
                    <p class="profile__rating-item user__rating-item user__rating-item--subscribers">
                        <span class="user__rating-amount">{{ $user->subscribers()->count() }}</span>
                        <span class="profile__rating-text user__rating-text">подписчиков</span>
                    </p>
                </div>

                <div class="profile__user-buttons user__buttons">

                    @if($user->id !== auth()->id())
                        @if($isSubscribed)

                            <button
                                class="user__button user__button--subscription button button--quartz"
                                onclick="document.getElementById('unsub-{{ $user->id }}').submit()"
                            >
                                Отписаться
                            </button>

                            <form id="unsub-{{ $user->id }}" action="{{ route('profile.unsubscribe', $user) }}" method="POST" hidden>
                                @csrf
                            </form>

                        @else

                            <button
                                class="user__button user__button--subscription button button--main"
                                onclick="document.getElementById('sub-{{ $user->id }}').submit()"
                            >
                                Подписаться
                            </button>

                            <form id="sub-{{ $user->id }}" action="{{ route('profile.subscribe', $user) }}" method="POST" hidden>
                                @csrf
                            </form>

                        @endif
                    @endif

                    <a class="user__button user__button--writing button button--green"
                       href="{{ route('messages.show', $user) }}">
                        Сообщение
                    </a>

                </div>

            </div>
        </div>
    </div>
        <div class="profile__tabs-wrapper tabs">
          <div class="container">
            <div class="profile__tabs filters">
              <b class="profile__tabs-caption filters__caption">Показать:</b>
                <ul class="profile__tabs-list filters__list tabs__list">
                    <li class="profile__tabs-item filters__item">
                        <a href="{{ route('profile.posts', $user) }}"
                           class="profile__tabs-link filters__button tabs__item button {{ $activeTab === 'posts' ? 'filters__button--active tabs__item--active' : '' }}">
                            Посты
                        </a>
                    </li>

                    <li class="profile__tabs-item filters__item">
                        <a href="{{ route('profile.likes', $user) }}"
                           class="profile__tabs-link filters__button tabs__item button {{ $activeTab === 'likes' ? 'filters__button--active tabs__item--active' : '' }}">
                            Лайки
                        </a>
                    </li>

                    <li class="profile__tabs-item filters__item">
                        <a href="{{ route('profile.subscriptions', $user) }}"
                           class="profile__tabs-link filters__button tabs__item button {{ $activeTab === 'subscriptions' ? 'filters__button--active tabs__item--active' : '' }}">
                            Подписки
                        </a>
                    </li>
                </ul>
            </div>
            <div class="profile__tab-content">

                @if($activeTab === 'posts')
                    <section class="profile__posts tabs__content tabs__content--active">
                        <h2 class="visually-hidden">Публикации</h2>

                        @forelse($posts as $post)
                            @include('profile.partials.post-card', ['post' => $post])
                        @empty
                            @include('feed.partials.empty')
                        @endforelse
                    </section>
                @endif

                @if($activeTab === 'likes')
                    <section class="profile__posts tabs__content tabs__content--active">
                        <h2 class="visually-hidden">Лайки</h2>

                        @forelse($likedPosts as $post)
                            @include('profile.partials.post-card', ['post' => $post])
                        @empty
                            @include('feed.partials.empty')
                        @endforelse
                    </section>
                @endif

                @if($activeTab === 'subscriptions')
                    <section class="profile__posts tabs__content tabs__content--active">
                        <h2 class="visually-hidden">Подписки</h2>

                        @forelse($subscriptions as $subUser)
                            @include('profile.partials.user-card', ['user' => $subUser])
                        @empty
                            @include('feed.partials.empty')
                        @endforelse
                    </section>
                @endif

            </div>

          </div>
        </div>
      </div>
    </main>
@endsection
