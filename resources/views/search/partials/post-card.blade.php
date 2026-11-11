<article class="search__post post post-{{ $post->type }}">
    <header class="post__header">

        @if($post->is_repost)

            {{-- Автор репоста --}}
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

            {{-- Автор оригинального поста --}}
            @php $original = $post->originalPost; @endphp

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

        @else

            {{-- Обычный пост --}}
            @php $original = $post; @endphp

            <div class="post__author">
                <a class="post__author-link"
                   href="{{ route('profile.posts', $post->author->id) }}">

                    <div class="post__avatar-wrapper">
                        <x-avatar :src="$post->author->avatar"
                                  class="post__author-avatar"
                                  alt="Аватар {{ $post->author->login }}" />
                    </div>

                    <div class="post__info">
                        <b class="post__author-name">{{ $post->author->login }}</b>
                        <time class="post__time" datetime="{{ $post->created_at }}">
                            {{ $post->created_at->diffForHumans() }}
                        </time>
                    </div>

                </a>
            </div>

        @endif

    </header>

    <div class="post__main">
        {{-- Контент: у репоста — контент оригинала --}}
        {!! $original->content_html ?? $original->content !!}
    </div>

    <footer class="post__footer">

        @php
            $tags = $post->is_repost
                ? $original->hashtags
                : $post->hashtags;
        @endphp

        @if($tags->isNotEmpty())
            <ul class="post__tags">
                @foreach($tags as $tag)
                    <li><a href="#">#{{ $tag->name }}</a></li>
                @endforeach
            </ul>
        @endif

    </footer>
</article>
