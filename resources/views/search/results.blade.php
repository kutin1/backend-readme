@extends('layouts.app')

@section('content')
<main class="page__main page__main--search-results">
    <h1 class="visually-hidden">Страница результатов поиска</h1>

    <section class="search">
        <h2 class="visually-hidden">Результаты поиска</h2>

        <div class="search__query-wrapper">
            <div class="search__query container">
                <span>Вы искали:</span>
                <span class="search__query-text">{{ $rawQuery }}</span>
            </div>
        </div>

        <div class="search__results-wrapper">
            <div class="container">
                <div class="search__content">

                    {{-- Пользователи --}}
                    @if($users->isNotEmpty())
                        <section class="feed__section">
                            <h3 class="feed__title">Пользователи</h3>
                            @include('search.partials.users', ['users' => $users])
                        </section>
                    @endif

                    {{-- Посты по тегу --}}
                    @if($tagPosts->isNotEmpty())
                        <section class="feed__section">
                            <h3 class="feed__title">Посты по тегу</h3>
                            @include('search.partials.list', ['posts' => $tagPosts])
                        </section>
                    @endif

                    {{-- Посты по тексту --}}
                    @if($posts->isNotEmpty())
                        <section class="feed__section">
                            <h3 class="feed__title">Посты</h3>
                            @include('search.partials.list', ['posts' => $posts])
                        </section>
                    @endif

                    {{-- Посты по комментариям --}}
                    @if($commentPosts->isNotEmpty())
                        <section class="feed__section">
                            <h3 class="feed__title">Совпадения в комментариях</h3>
                            @include('search.partials.list', ['posts' => $commentPosts])
                        </section>
                    @endif

                    {{-- Пусто --}}
                    @if(
                        $users->isEmpty() &&
                        $tagPosts->isEmpty() &&
                        $posts->isEmpty() &&
                        $commentPosts->isEmpty()
                    )
                        @include('search.partials.empty')
                    @endif

                </div>
            </div>
        </div>

    </section>
</main>
@endsection
