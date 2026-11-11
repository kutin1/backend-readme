<article class="search__post post post-{{ $post->contentType->icon }}">

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
                            Репост: {!! highlight($post->author->login, $query) !!}
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
                        <b class="post__author-name">{!! highlight($original->author->login, $query) !!}</b>
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
        @include('search.partials.post-author', ['post' => $post, 'query' => $query])
    @endif


    <div class="post__main">
        <blockquote>
            <p>{!! highlight($original->content, $query) !!}</p>
            <cite>{!! highlight($original->quote_author, $query) !!}</cite>
        </blockquote>
    </div>

    {{-- Футер всегда от репоста/поста --}}
    @include('search.partials.post-footer', ['post' => $post, 'query' => $query])

</article>
