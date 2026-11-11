<p>Здравствуйте, {{ $recipient->login }}.</p>

<p>
    Пользователь <strong>{{ $post->author->login }}</strong>
    только что опубликовал новую запись «{{ $post->title }}».
</p>

<p>
    Посмотрите её на странице пользователя:
    <a href="{{ route('profile.posts', $post->author->id) }}">
        {{ route('profile.posts', $post->author->id) }}
    </a>
</p>
