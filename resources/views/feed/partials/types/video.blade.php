<article class="feed__post post post-video">

    {{-- РЕПОСТ --}}
    @if($post->is_repost)

        {{-- Автор репоста --}}
        <header class="post__header">
            <div class="post__author">
                <a class="post__author-link"
                   href="{{ route('profile.posts', $post->author->id) }}"
                   title="Автор репоста">

                    <div class="post__avatar-wrapper post__avatar-wrapper--repost">
                        <x-avatar :src="$post->author->avatar"
                            class="post__author-avatar"
                            alt="Аватар {{ $post->author->login }}" />
                    </div>

                    <div class="post__info">
                        <b class="post__author-name">
                            Репост: {{ $post->author->login }}
                        </b>
                        <time class="post__time" datetime="{{ $post->created_at }}">
                            {{ $post->created_at->diffForHumans() }}
                        </time>
                    </div>

                </a>
            </div>
        </header>

        {{-- Оригинальный пост --}}
        @php
            $original = $post->originalPost;
        @endphp

        <header class="post__header">
            <div class="post__author">
                <a class="post__author-link"
                   href="{{ route('profile.posts', $original->author->id) }}"
                   title="Автор оригинала">

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

    @else
        {{-- Обычный пост --}}
        @php $original = $post; @endphp
        @include('feed.partials.post-author', ['post' => $post])
    @endif


    <div class="post__main">
        <div class="post-video__block">

            <div class="post-video__preview">
                <img src="{{ $original->image
                            ? asset('storage/' . $original->image)
                            : asset('build/img/video-preview.jpg') }}"
                     alt="Превью к видео"
                     width="760" height="396">
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
    </div>

    {{-- Футер всегда от репоста/поста --}}
    @include('feed.partials.post-footer', ['post' => $post])

</article>
