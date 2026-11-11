<article class="feed__post post post-link">

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
        <div class="post-link__wrapper">
            <a class="post-link__external" href="{{ $original->link }}" target="_blank">

                <div class="post-link__icon-wrapper">
                    <img src="{{ asset('storage/' . $original->image) }}"
                         alt="Иконка">
                </div>

                <div class="post-link__info">
                    <h3>{{ $original->title }}</h3>

                    @if($original->content)
                        <p>{{ $original->content }}</p>
                    @endif

                    <span>{{ parse_url($original->link, PHP_URL_HOST) }}</span>
                </div>

                <svg class="post-link__arrow" width="11" height="16">
                    <use xlink:href="#icon-arrow-right-ad"></use>
                </svg>

            </a>
        </div>
    </div>

    {{-- Футер всегда от репоста/поста --}}
    @include('feed.partials.post-footer', ['post' => $post])

</article>
