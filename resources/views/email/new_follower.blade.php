<p>Здравствуйте, {{ $recipient->login }}.</p>

<p>
    На вас подписался новый пользователь <strong>{{ $follower->login }}</strong>.
</p>

<p>
    Вот ссылка на его профиль:
    <a href="{{ route('profile.posts', $follower->id) }}">
        {{ route('profile.posts', $follower->id) }}
    </a>
</p>
