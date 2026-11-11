<article class="popular__post post post-link">

    <header class="post__header">
        <h2>
            <a href="{{ route('post.show', $post->id) }}">
                {{ $post->title }}
            </a>
        </h2>
    </header>

    <div class="post__main">
        <div class="post-link__wrapper">
            <a class="post-link__external"
               href="{{ $post->link }}"
               target="_blank"
               rel="noopener"
               title="Перейти по ссылке">

                <div class="post-link__info-wrapper">

                    @if($post->image)
                        <div class="post-link__icon-wrapper">
                            <img src="{{ asset('storage/' . $post->image) }}"
                                alt="Превью сайта {{ $post->title ?? '' }}">
                        </div>
                    @endif

                    <div class="post-link__info">
                        <h3>{{ $post->title }}</h3>
                        <p>Перейти по ссылке</p>
                    </div>

                </div>

                <span>{{ parse_url($post->link, PHP_URL_HOST) }}</span>
            </a>
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
