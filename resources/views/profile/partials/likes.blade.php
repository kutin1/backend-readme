<section class="profile__likes tabs__content">
    @if($likedPosts->isEmpty())
        @include('feed.partials.empty')
    @else
        @foreach($likedPosts as $post)
            @include('feed.partials.post-card', ['post' => $post])
        @endforeach
    @endif
</section>
