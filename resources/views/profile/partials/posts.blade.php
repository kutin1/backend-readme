<section class="profile__posts tabs__content tabs__content--active">
    @if($posts->isEmpty())
        @include('feed.partials.empty')
    @else
        @foreach($posts as $post)
            @include('profile.partials.post-card', ['post' => $post])
        @endforeach
    @endif
</section>
