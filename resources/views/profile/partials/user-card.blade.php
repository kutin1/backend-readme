<div class="post-mini post-mini--photo post user">
    <div class="post-mini__user-info user__info">
        <div class="post-mini__avatar user__avatar">
            <a class="user__avatar-link" href="{{ route('profile.posts', $user->id) }}">
                <x-avatar :src="$user->avatar"
                    class="post-mini__picture user__picture"
                    alt="Аватар {{ $user->login }}" />
            </a>
        </div>
        <div class="post-mini__name-wrapper user__name-wrapper">
            <a class="post-mini__name user__name" href="{{ route('profile.posts', $user->id) }}">
                <span>{{ $user->login }}</span>
            </a>
            <time class="post-mini__time user__additional">
                {{ $user->created_at->diffForHumans() }}
            </time>
        </div>
    </div>
</div>
