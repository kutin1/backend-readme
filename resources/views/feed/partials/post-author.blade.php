<header class="post__header post__author">
    <a class="post__author-link" href="{{ route('profile.posts', $post->author->id) }}">
        <div class="post__avatar-wrapper">
            <x-avatar :src="$post->author->avatar"
                class="post__author-avatar"
                alt="Аватар {{ $post->author->login }}" />
        </div>
        <div class="post__info">
            <b class="post__author-name">{{ $post->author->login }}</b>
            <span class="post__time">{{ $post->created_at->diffForHumans() }}</span>
        </div>
    </a>
</header>
