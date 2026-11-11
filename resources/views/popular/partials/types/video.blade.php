<article class="popular__post post post-video">

    <header class="post__header">
        <h2>
            <a href="{{ route('post.show', $post->id) }}">
                {{ $post->title }}
            </a>
        </h2>
    </header>

    <div class="post__main">
        <div class="post-video__block">

            {{-- Превью видео --}}
            <div class="post-video__preview">
                <img
                    src="{{ asset('storage/' . $post->image) }}"
                    alt="Превью к видео"
                    width="360"
                    height="188"
                >
            </div>

            {{-- Панель управления (статичная, как в макете) --}}
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
                <svg class="post-video__play-big-icon" width="14" height="14">
                    <use xlink:href="#icon-video-play-big"></use>
                </svg>
                <span class="visually-hidden">Запустить проигрыватель</span>
            </button>

        </div>
    </div>

    <footer class="post__footer">

        <div class="post__author">
            <a class="post__author-link"
               href="{{ route('profile.posts', $post->author->id) }}"
               title="Автор">

                <div class="post__avatar-wrapper">
                    <x-avatar :src="$post->author->avatar"
                              class="post__author-avatar"
                              alt="Аватар {{ $post->author->login }}"
                              width="40"
                              height="40"
                    />
                </div>

                <div class="post__info">
                    <b class="post__author-name">{{ $post->author->login }}</b>
                    <time class="post__time"
                          datetime="{{ $post->created_at }}">
                        {{ $post->created_at->diffForHumans() }}
                    </time>
                </div>

            </a>
        </div>

        <div class="post__indicators">
            <div class="post__buttons">

                {{-- Лайки --}}
                <form action="{{ route('post.like', $post) }}" method="POST" style="display:inline;">
                    @csrf

                    @php
                        $liked = $post->likes()->where('user_id', auth()->id())->exists();
                    @endphp

                    <button class="post__indicator post__indicator--likes button {{ $liked ? 'post__indicator--active' : '' }}"
                            title="Лайк">
                        <svg class="post__indicator-icon" width="20" height="17">
                            <use xlink:href="#icon-heart"></use>
                        </svg>
                        <svg class="post__indicator-icon post__indicator-icon--like-active" width="20" height="17">
                            <use xlink:href="#icon-heart-active"></use>
                        </svg>
                        <span>{{ $post->likes_count }}</span>
                    </button>
                </form>

                {{-- Комменты --}}
                <a class="post__indicator post__indicator--comments button"
                   href="{{ route('post.show', $post->id) }}"
                   title="Комментарии">
                    <svg class="post__indicator-icon" width="19" height="17">
                        <use xlink:href="#icon-comment"></use>
                    </svg>
                    <span>{{ $post->comments_count }}</span>
                </a>

            </div>
        </div>

    </footer>

</article>
