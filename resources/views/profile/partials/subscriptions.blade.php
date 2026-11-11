<section class="profile__subscriptions tabs__content">
    @if($subscriptions->isEmpty())
        @include('feed.partials.empty')
    @else
        @foreach($subscriptions as $subUser)
            @include('profile.partials.user-card', ['user' => $subUser])
        @endforeach
    @endif
</section>
