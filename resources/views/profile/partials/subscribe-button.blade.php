@if($user->id !== auth()->id())

    @php
        $isSubscribed = auth()->user()
            ->subscriptions()
            ->where('target_id', $user->id)
            ->exists();
    @endphp

    @if($isSubscribed)

        <button
            class="user__button user__button--subscription button button--quartz"
            onclick="document.getElementById('unsub-{{ $user->id }}').submit()"
        >
            Отписаться
        </button>

        <form id="unsub-{{ $user->id }}" action="{{ route('profile.unsubscribe', $user) }}" method="POST" hidden>
            @csrf
        </form>

    @else

        <button
            class="user__button user__button--subscription button button--main"
            onclick="document.getElementById('sub-{{ $user->id }}').submit()"
        >
            Подписаться
        </button>

        <form id="sub-{{ $user->id }}" action="{{ route('profile.subscribe', $user) }}" method="POST" hidden>
            @csrf
        </form>

    @endif

@endif
