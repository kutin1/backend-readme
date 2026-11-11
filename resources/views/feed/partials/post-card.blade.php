<article class="post">
    <header class="post__header">

        {{-- Репост --}}
        @if($post->is_repost)
            <div class="post__author">
                <a class="post__author-link"
                   href="{{ route('profile.posts', $post->author->id) }}">

                    {{-- Аватар того, кто сделал репост --}}
                    <div class="post__avatar-wrapper post__avatar-wrapper--repost">
                        <img class="post__author-avatar"
                             src="{{ $post->author->avatar
                                    ? asset('storage/' . $post->author->avatar)
                                    : asset('build/img/userpic.jpg') }}"
                             alt="Аватар {{ $post->author->login }}">
                    </div>

                    <div class="post__info">
                        <b class="post__author-name">
                            Репост: {{ $post->author->login }}
                        </b>

                        <time class="post__time"
                              datetime="{{ $post->created_at }}">
                            {{ $post->created_at->diffForHumans() }}
                        </time>
                    </div>

                </a>
            </div>

        {{-- Обычный пост --}}
        @else
            <div class="post__author">
                <a class="post__author-link"
                   href="{{ route('profile.posts', $post->author->id) }}">

                    <div class="post__avatar-wrapper">
                        <img class="post__author-avatar"
                             src="{{ $post->author->avatar
                                    ? asset('storage/' . $post->author->avatar)
                                    : asset('build/img/userpic.jpg') }}"
                             alt="Аватар {{ $post->author->login }}">
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
        @endif

    </header>

    <div class="post__main">

        {{-- Контент репоста = контент оригинального поста --}}
        @if($post->is_repost)
            {!! $post->originalPost->content_html ?? $post->originalPost->content !!}
        @else
            {!! $post->content_html ?? $post->content !!}
        @endif

    </div>

    <footer class="post__footer">

        {{-- Теги: у репоста — теги оригинала --}}
        @php
            $tags = $post->is_repost
                ? $post->originalPost->hashtags
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
