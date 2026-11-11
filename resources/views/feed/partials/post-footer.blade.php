<footer class="post__footer post__indicators">
    <div class="post__buttons">

        {{-- Лайки --}}
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

        {{-- Комментарии --}}
        <a class="post__indicator post__indicator--comments button" href="{{ route('post.show', $post) }}#comment-field" title="Комментарии">
            <svg class="post__indicator-icon" width="19" height="17">
                <use xlink:href="#icon-comment"></use>
            </svg>
            <span>{{ $post->comments()->count() }}</span>
            <span class="visually-hidden">количество комментариев</span>
        </a>

        {{-- Репосты --}}
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

    {{-- Хэштеги (дополнительный блок) --}}
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
