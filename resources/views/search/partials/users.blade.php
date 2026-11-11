<section class="search__users">
    <h2 class="visually-hidden">Пользователи</h2>

    @foreach($users as $user)
        @include('search.partials.user-card', [
            'user' => $user,
            'query' => $query
        ])
    @endforeach
</section>
