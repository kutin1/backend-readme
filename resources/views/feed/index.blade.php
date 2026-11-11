@extends('layouts.app')

@section('content')
    <main class="page__main page__main--feed">
      <div class="container">
        <h1 class="page__title page__title--feed">Моя лента</h1>
      </div>
      <div class="page__main-wrapper container">
        <section class="feed">
          <h2 class="visually-hidden">Лента</h2>
          <div class="feed__main-wrapper">
            <div class="feed__wrapper">
                @if($posts->isEmpty())
                    @include('feed.partials.empty')
                @else
                    @foreach($posts as $post)
                        @include('feed.partials.types.' . $post->contentType->icon, ['post' => $post])
                    @endforeach
                @endif
            </div>
          </div>
          <ul class="feed__filters filters">
    <li class="feed__filters-item filters__item">
        <a class="filters__button {{ $type === 'all' ? 'filters__button--active' : '' }}"
           href="{{ route('feed', ['type' => 'all']) }}">
            <span>Все</span>
        </a>
    </li>

    <li class="feed__filters-item filters__item">
        <a class="filters__button filters__button--photo button {{ $type === 'photo' ? 'filters__button--active' : '' }}"
           href="{{ route('feed', ['type' => 'photo']) }}">
            <span class="visually-hidden">Фото</span>
            <svg class="filters__icon" width="22" height="18">
                <use xlink:href="#icon-filter-photo"></use>
            </svg>
        </a>
    </li>

    <li class="feed__filters-item filters__item">
        <a class="filters__button filters__button--video button {{ $type === 'video' ? 'filters__button--active' : '' }}"
           href="{{ route('feed', ['type' => 'video']) }}">
            <span class="visually-hidden">Видео</span>
            <svg class="filters__icon" width="24" height="16">
                <use xlink:href="#icon-filter-video"></use>
            </svg>
        </a>
    </li>

    <li class="feed__filters-item filters__item">
        <a class="filters__button filters__button--text button {{ $type === 'text' ? 'filters__button--active' : '' }}"
           href="{{ route('feed', ['type' => 'text']) }}">
            <span class="visually-hidden">Текст</span>
            <svg class="filters__icon" width="20" height="21">
                <use xlink:href="#icon-filter-text"></use>
            </svg>
        </a>
    </li>

    <li class="feed__filters-item filters__item">
        <a class="filters__button filters__button--quote button {{ $type === 'quote' ? 'filters__button--active' : '' }}"
           href="{{ route('feed', ['type' => 'quote']) }}">
            <span class="visually-hidden">Цитата</span>
            <svg class="filters__icon" width="21" height="20">
                <use xlink:href="#icon-filter-quote"></use>
            </svg>
        </a>
    </li>

    <li class="feed__filters-item filters__item">
        <a class="filters__button filters__button--link button {{ $type === 'link' ? 'filters__button--active' : '' }}"
           href="{{ route('feed', ['type' => 'link']) }}">
            <span class="visually-hidden">Ссылка</span>
            <svg class="filters__icon" width="21" height="18">
                <use xlink:href="#icon-filter-link"></use>
            </svg>
        </a>
    </li>
</ul>

        </section>
        <aside class="promo">
          <article class="promo__block promo__block--barbershop">
            <h2 class="visually-hidden">Рекламный блок</h2>
            <p class="promo__text">
              Все еще сидишь на окладе в офисе? Открой свой барбершоп по нашей франшизе!
            </p>
            <a class="promo__link" href="#">
              Подробнее
            </a>
          </article>
          <article class="promo__block promo__block--technomart">
            <h2 class="visually-hidden">Рекламный блок</h2>
            <p class="promo__text">
              Товары будущего уже сегодня в онлайн-сторе Техномарт!
            </p>
            <a class="promo__link" href="#">
              Перейти в магазин
            </a>
          </article>
          <article class="promo__block">
            <h2 class="visually-hidden">Рекламный блок</h2>
            <p class="promo__text">
              Здесь<br> могла быть<br> ваша реклама
            </p>
            <a class="promo__link" href="#">
              Разместить
            </a>
          </article>
        </aside>
      </div>
    </main>
@endsection
