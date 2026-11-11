<article class="profile__post post post-{{ $post->contentType->icon }}">

    @if($post->is_repost)

        {{-- Автор репоста --}}
        <header class="post__header">
            <div class="post__author">
                <a class="post__author-link"
                   href="{{ route('profile.posts', $post->author->id) }}">

                    <div class="post__avatar-wrapper post__avatar-wrapper--repost">
                        <x-avatar :src="$post->author->avatar"
                            class="post__author-avatar"
                            alt="Аватар {{ $post->author->login }}" />
                    </div>

                    <div class="post__info">
                        <b class="post__author-name">Репост: {{ $post->author->login }}</b>
                        <time class="post__time" datetime="{{ $post->created_at }}">
                            {{ $post->created_at->diffForHumans() }}
                        </time>
                    </div>

                </a>
            </div>
        </header>

        @php
            $original = $post->originalPost;
        @endphp

        {{-- Автор оригинала --}}
        <header class="post__header">
            <div class="post__author">
                <a class="post__author-link"
                   href="{{ route('profile.posts', $original->author->id) }}">

                    <div class="post__avatar-wrapper">
                        <x-avatar :src="$original->author->avatar"
                            class="post__author-avatar"
                            alt="Аватар {{ $original->author->login }}" />
                    </div>

                    <div class="post__info">
                        <b class="post__author-name">{{ $original->author->login }}</b>
                        <time class="post__time" datetime="{{ $original->created_at }}">
                            {{ $original->created_at->diffForHumans() }}
                        </time>
                    </div>

                </a>
            </div>
        </header>

        {{-- Заголовок оригинального поста --}}
        <header class="post__header">
            <h2>
                <a href="{{ route('post.show', $original) }}">
                    {{ $original->title }}
                </a>
            </h2>
        </header>

    @else

        @php $original = $post; @endphp

        {{-- Заголовок обычного поста --}}
        <header class="post__header">
            <h2>
                <a href="{{ route('post.show', $post) }}">
                    {{ $post->title }}
                </a>
            </h2>
        </header>

    @endif

    {{-- Контент --}}
    <div class="post__main">

        {{-- Фото --}}
        @if($original->content_type_id == 3)
            <div class="post-photo__image-wrapper">
                  <img src="{{ asset('storage/' . $original->image) }}"
                     alt="Фото"
                     width="760">
            </div>
        @endif

        {{-- Текст --}}
        @if($original->content_type_id == 1)
            <p>{{ $original->content }}</p>
        @endif

        {{-- Цитата --}}
        @if($original->content_type_id == 2)
            <blockquote class="post-quote">
                <p>{{ $original->content }}</p>
                <cite>{{ $original->quote_author }}</cite>
            </blockquote>
        @endif

        {{-- Видео --}}
        @if($original->content_type_id == 4)
            <div class="post-video__block">

                {{-- Превью --}}
                <div class="post-video__preview">
                    <img src="{{ asset('storage/' . $original->image) }}" width="760" alt="Видео превью">
                </div>

                {{-- Полный видеоконтроллер --}}
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

                {{-- Большая кнопка Play --}}
                <button class="post-video__play-big button" type="button">
                    <svg class="post-video__play-big-icon" width="27" height="28">
                        <use xlink:href="#icon-video-play-big"></use>
                    </svg>
                    <span class="visually-hidden">Запустить проигрыватель</span>
                </button>

            </div>
        @endif

        {{-- Ссылка --}}
        @if($original->content_type_id == 5)
            <div class="post-link__wrapper">
                <a class="post-link__external" href="{{ $original->link }}" target="_blank">

                    {{-- Превью (иконка) --}}
                    @if($original->image)
                        <div class="post-link__icon-wrapper">
                            <img src="{{ asset('storage/' . $original->image) }}" alt="Иконка">
                        </div>
                    @endif

                    {{-- Информация --}}
                    <div class="post-link__info">
                        <h3>{{ $original->title }}</h3>
                        <span>{{ parse_url($original->link, PHP_URL_HOST) }}</span>
                    </div>

                    {{-- Стрелка --}}
                    <svg class="post-link__arrow" width="11" height="16">
                        <use xlink:href="#icon-arrow-right-ad"></use>
                    </svg>

                </a>
            </div>
        @endif

    </div>

    {{-- Футер --}}
    <footer class="post__footer">

        {{-- Индикаторы --}}
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

            <time class="post__time" datetime="{{ $post->created_at }}">
                {{ $post->created_at->diffForHumans() }}
            </time>
        </div>

        {{-- Хэштеги --}}
        @if($post->hashtags->isNotEmpty())
            <ul class="post__tags">
                @foreach($post->hashtags as $tag)
                    <li>
                        <a href="{{ route('search', ['q' => '#' . ltrim($tag->name, '#')]) }}">
                            #{{ ltrim($tag->name, '#') }}
                        </a>
                    </li>
                @endforeach
            </ul>
        @endif

    </footer>

    {{-- Кнопка комментариев --}}
    <div class="comments">
        <a class="comments__button button"
           href="{{ route('post.show', $post) }}">
            Показать комментарии
        </a>
    </div>

</article>
