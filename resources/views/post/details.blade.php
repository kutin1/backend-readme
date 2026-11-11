@extends('layouts.app')

@section('content')
<main class="page__main page__main--publication">
    <div class="container">

        {{-- Заголовок --}}
        <h1 class="page__title page__title--publication">
            {{ $post->title }}
        </h1>

        <section class="post-details">
            <h2 class="visually-hidden">Публикация</h2>

            <div class="post-details__wrapper post-{{ $post->contentType->icon }}">

                {{-- ЛЕВАЯ ЧАСТЬ --}}
                <div class="post-details__main-block post post--details">

                    {{-- Репост --}}
                    @if($post->is_repost)
                        <header class="post__header">
                            <div class="post__author">
                                <a class="post__author-link"
                                   href="{{ route('profile.posts', $post->originalPost->author) }}"
                                   title="Автор">
                                    <div class="post__avatar-wrapper post__avatar-wrapper--repost">
                                        <x-avatar :src="$post->originalPost->author->avatar"
                                            class="post__author-avatar"
                                            alt="Аватар пользователя" />
                                    </div>
                                    <div class="post__info">
                                        <b class="post__author-name">
                                            Репост: {{ $post->originalPost->author->login }}
                                        </b>
                                        <time class="post__time"
                                              datetime="{{ $post->originalPost->created_at->toDateString() }}">
                                            {{ $post->originalPost->created_at->diffForHumans() }}
                                        </time>
                                    </div>
                                </a>
                            </div>
                        </header>
                    @endif

                    {{-- === PHOTO === --}}
                    @if($post->content_type_id == 3)
                        <div class="post-details__image-wrapper post-photo__image-wrapper">
                            <img src="{{ asset('storage/' . $post->image) }}"
                                 alt="Фото от пользователя"
                                 width="760">
                        </div>
                    @endif

                    {{-- === TEXT === --}}
                    @if($post->content_type_id == 1)
                        <div class="post-details__text-wrapper">
                            <p>{{ $post->content }}</p>
                        </div>
                    @endif

                    {{-- === QUOTE === --}}
                    @if($post->content_type_id == 2)
                    <div class="post__main">
                        <blockquote class="post-quote">
                            <p>{{ $post->content }}</p>
                            <cite>{{ $post->quote_author }}</cite>
                        </blockquote>
                    </div>
                    @endif

                    {{-- === VIDEO === --}}
                    @if($post->content_type_id == 4)
                        <div class="post-video__block">

                            <div class="post-video__preview">
                                <img src="{{ asset('storage/' . $post->image) }}"
                                     alt="Видео превью"
                                     width="760">
                            </div>

                            <div class="post-video__control">
                                <button class="post-video__play post-video__play--paused button button--video" type="button">
                                    <span class="visually-hidden">Запустить видео</span>
                                </button>

                                <div class="post-video__scale-wrapper">
                                    <div class="post-video__scale">
                                        <div class="post-video__bar">
                                            <div class="post-video__toggle"></div>
                                        </div>
                                    </div>
                                </div>

                                <button class="post-video__fullscreen post-video__fullscreen--inactive button button--video" type="button">
                                    <span class="visually-hidden">Полноэкранный режим</span>
                                </button>
                            </div>

                            <button class="post-video__play-big button" type="button">
                                <svg class="post-video__play-big-icon" width="27" height="28">
                                    <use xlink:href="#icon-video-play-big"></use>
                                </svg>
                                <span class="visually-hidden">Запустить проигрыватель</span>
                            </button>

                        </div>
                    @endif


                    {{-- === LINK === --}}
                    @if($post->content_type_id == 5)
                        <div class="post-link__wrapper">
                            <a class="post-link__external" href="{{ $post->link }}" target="_blank">

                                @if($post->image)
                                    <div class="post-link__icon-wrapper">
                                        <img src="{{ asset('storage/' . $post->image) }}"
                                             alt="Иконка">
                                    </div>
                                @endif

                                <div class="post-link__info">
                                    <h3>{{ $post->title }}</h3>

                                    @if($post->content)
                                        <p>{{ $post->content }}</p>
                                    @endif

                                    <span>{{ parse_url($post->link, PHP_URL_HOST) }}</span>
                                </div>

                                <svg class="post-link__arrow" width="11" height="16">
                                    <use xlink:href="#icon-arrow-right-ad"></use>
                                </svg>

                            </a>
                        </div>
                    @endif

                    {{-- ИНДИКАТОРЫ --}}

                    <div class="post__indicators">
                        <div class="post__buttons">

                            <form action="{{ route('post.like', $post) }}" method="POST" style="display:inline;">
                                @csrf

                                @php
                                    $liked = $post->likes()->where('user_id', auth()->id())->exists();
                                @endphp

                                <button type="submit"
                                        class="post__indicator post__indicator--likes button {{ $liked ? 'post__indicator--active' : '' }}"
                                        title="Лайк"
                                        style="background:none; border:none; padding:0; cursor:pointer;">
                                    <svg class="post__indicator-icon" width="20" height="17">
                                        <use xlink:href="#icon-heart"></use>
                                    </svg>
                                    <svg class="post__indicator-icon post__indicator-icon--like-active" width="20" height="17">
                                        <use xlink:href="#icon-heart-active"></use>
                                    </svg>
                                    <span>{{ $post->likes()->count() }}</span>
                                    <span class="visually-hidden">количество лайков</span>
                                </button>
                            </form>

                            <a class="post__indicator post__indicator--comments button" href="#comment-field" title="Комментарии">
                                <svg class="post__indicator-icon" width="19" height="17">
                                    <use xlink:href="#icon-comment"></use>
                                </svg>
                                <span>{{ $post->comments()->count() }}</span>
                                <span class="visually-hidden">количество комментариев</span>
                            </a>

                            <form action="{{ route('post.repost', $post) }}" method="POST" style="display:inline;">
                                @csrf
                                <button type="submit"
                                        class="post__indicator post__indicator--repost button"
                                        title="Репост"
                                        style="background:none; border:none; padding:0; cursor:pointer;">
                                    <svg class="post__indicator-icon" width="19" height="17">
                                        <use xlink:href="#icon-repost"></use>
                                    </svg>
                                    <span>{{ $post->reposts()->count() }}</span>
                                    <span class="visually-hidden">количество репостов</span>
                                </button>
                            </form>

                        </div>

                        <span class="post__view">{{ $post->views }} просмотров</span>
                    </div>


                    {{-- ХЭШТЕГИ --}}
                    <footer class="post__footer">
                        @if($post->hashtags->isNotEmpty())
                            <ul class="post__tags">
                                @foreach($post->hashtags as $tag)
                                    <li class="post__tags-item">
                                        <a href="{{ route('search', ['q' => '#' . ltrim($tag->name, '#')]) }}">
                                            #{{ ltrim($tag->name, '#') }}
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                    </footer>


                    {{-- КОММЕНТАРИИ --}}
                    @include('post.partials.comments', ['post' => $post])

                </div>

                {{-- ПРАВАЯ ЧАСТЬ — АВТОР --}}
                <div class="post-details__user user">

                    <div class="post-details__user-info user__info">

                        <div class="post-details__avatar user__avatar">
                            <a class="post-details__avatar-link user__avatar-link"
                               href="{{ route('profile.posts', $post->author) }}">
                                <x-avatar :src="$post->author->avatar"
                                    class="post-details__picture user__picture"
                                    alt="Аватар пользователя" />
                            </a>
                        </div>

                        <div class="post-details__name-wrapper user__name-wrapper">
                            <a class="post-details__name user__name"
                               href="{{ route('profile.posts', $post->author) }}">
                                <span>{{ $post->author->login }}</span>
                            </a>

                            <time class="post-details__time user__time"
                                  datetime="{{ $post->author->created_at->toDateString() }}">
                                {{ $post->author->years_on_site }} {{ $post->author->years_on_site_text }} на сайте
                            </time>
                        </div>

                    </div>

                    <div class="post-details__rating user__rating">

                        <p class="post-details__rating-item user__rating-item user__rating-item--subscribers">
                            <span class="post-details__rating-amount user__rating-amount">
                                {{ $post->author->subscribers()->count() }}
                            </span>
                            <span class="post-details__rating-text user__rating-text">подписчиков</span>
                        </p>

                        <p class="post-details__rating-item user__rating-item user__rating-item--publications">
                            <span class="post-details__rating-amount user__rating-amount">
                                {{ $post->author->posts()->count() }}
                            </span>
                            <span class="post-details__rating-text user__rating-text">публикаций</span>
                        </p>

                    </div>

                    <div class="post-details__user-buttons user__buttons">
                        @include('profile.partials.subscribe-button', ['user' => $post->author])
                        <a class="user__button user__button--writing button button--green"
                           href="{{ route('messages.show', $post->author) }}">
                            Сообщение
                        </a>
                    </div>

                </div>


            </div>
        </section>

    </div>
</main>
@endsection
