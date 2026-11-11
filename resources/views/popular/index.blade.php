@extends('layouts.app')

@section('content')

    <section class="page__main page__main--popular">
      <div class="container">
        <h1 class="page__title page__title--popular">Популярное</h1>
      </div>
      <div class="popular container">
        <div class="popular__filters-wrapper">
          <div class="popular__sorting sorting">
            <b class="popular__sorting-caption sorting__caption">Сортировка:</b>
            <ul class="popular__sorting-list sorting__list">
              <li class="sorting__item sorting__item--popular">
                  <a class="sorting__link {{ $sort === 'popular' ? 'sorting__link--active' : '' }}"
                     href="{{ route('popular.index', [
                         'sort' => 'popular',
                         'type' => $type,
                         'direction' => $sort === 'popular' && $direction === 'desc' ? 'asc' : 'desc'
                     ]) }}">
                      <span>Популярность</span>
                      <svg class="sorting__icon"
                           width="10"
                           height="12"
                           style="{{ $sort === 'popular' && $direction === 'asc' ? 'transform: rotate(180deg) translateY(50%);' : '' }}">
                          <use xlink:href="#icon-sort"></use>
                      </svg>
                  </a>
              </li>

              <li class="sorting__item">
                  <a class="sorting__link {{ $sort === 'likes' ? 'sorting__link--active' : '' }}"
                     href="{{ route('popular.index', [
                         'sort' => 'likes',
                         'type' => $type,
                         'direction' => $sort === 'likes' && $direction === 'desc' ? 'asc' : 'desc'
                     ]) }}">
                      <span>Лайки</span>
                      <svg class="sorting__icon"
                           width="10"
                           height="12"
                           style="{{ $sort === 'likes' && $direction === 'asc' ? 'transform: rotate(180deg) translateY(50%);' : '' }}">
                          <use xlink:href="#icon-sort"></use>
                      </svg>
                  </a>
              </li>

              <li class="sorting__item">
                  <a class="sorting__link {{ $sort === 'date' ? 'sorting__link--active' : '' }}"
                     href="{{ route('popular.index', [
                         'sort' => 'date',
                         'type' => $type,
                         'direction' => $sort === 'date' && $direction === 'desc' ? 'asc' : 'desc'
                     ]) }}">
                      <span>Дата</span>
                      <svg class="sorting__icon"
                           width="10"
                           height="12"
                           style="{{ $sort === 'date' && $direction === 'asc' ? 'transform: rotate(180deg) translateY(50%);' : '' }}">
                          <use xlink:href="#icon-sort"></use>
                      </svg>
                  </a>
              </li>
            </ul>
          </div>
          <div class="popular__filters filters">
            <b class="popular__filters-caption filters__caption">Тип контента:</b>
            <ul class="popular__filters-list filters__list">

                {{-- Все --}}
                <li class="popular__filters-item filters__item">
                    <a class="filters__button filters__button--ellipse filters__button--all {{ $type === 'all' ? 'filters__button--active' : '' }}"
                       href="{{ route('popular.index', [
                           'sort' => $sort,
                           'direction' => $direction,
                           'type' => 'all'
                       ]) }}">
                        <span>Все</span>
                    </a>
                </li>

                {{-- Фото --}}
                <li class="popular__filters-item filters__item">
                    <a class="filters__button filters__button--photo button {{ $type === 'photo' ? 'filters__button--active' : '' }}"
                       href="{{ route('popular.index', [
                           'sort' => $sort,
                           'direction' => $direction,
                           'type' => 'photo'
                       ]) }}">
                        <span class="visually-hidden">Фото</span>
                        <svg class="filters__icon" width="22" height="18">
                            <use xlink:href="#icon-filter-photo"></use>
                        </svg>
                    </a>
                </li>

                {{-- Видео --}}
                <li class="popular__filters-item filters__item">
                    <a class="filters__button filters__button--video button {{ $type === 'video' ? 'filters__button--active' : '' }}"
                       href="{{ route('popular.index', [
                           'sort' => $sort,
                           'direction' => $direction,
                           'type' => 'video'
                       ]) }}">
                        <span class="visually-hidden">Видео</span>
                        <svg class="filters__icon" width="24" height="16">
                            <use xlink:href="#icon-filter-video"></use>
                        </svg>
                    </a>
                </li>

                {{-- Текст --}}
                <li class="popular__filters-item filters__item">
                    <a class="filters__button filters__button--text button {{ $type === 'text' ? 'filters__button--active' : '' }}"
                       href="{{ route('popular.index', [
                           'sort' => $sort,
                           'direction' => $direction,
                           'type' => 'text'
                       ]) }}">
                        <span class="visually-hidden">Текст</span>
                        <svg class="filters__icon" width="20" height="21">
                            <use xlink:href="#icon-filter-text"></use>
                        </svg>
                    </a>
                </li>

                {{-- Цитата --}}
                <li class="popular__filters-item filters__item">
                    <a class="filters__button filters__button--quote button {{ $type === 'quote' ? 'filters__button--active' : '' }}"
                       href="{{ route('popular.index', [
                           'sort' => $sort,
                           'direction' => $direction,
                           'type' => 'quote'
                       ]) }}">
                        <span class="visually-hidden">Цитата</span>
                        <svg class="filters__icon" width="21" height="20">
                            <use xlink:href="#icon-filter-quote"></use>
                        </svg>
                    </a>
                </li>

                {{-- Ссылка --}}
                <li class="popular__filters-item filters__item">
                    <a class="filters__button filters__button--link button {{ $type === 'link' ? 'filters__button--active' : '' }}"
                       href="{{ route('popular.index', [
                           'sort' => $sort,
                           'direction' => $direction,
                           'type' => 'link'
                       ]) }}">
                        <span class="visually-hidden">Ссылка</span>
                        <svg class="filters__icon" width="21" height="18">
                            <use xlink:href="#icon-filter-link"></use>
                        </svg>
                    </a>
                </li>

            </ul>
        </div>

        </div>
        <div class="popular__posts">
            @foreach ($posts as $post)
                @include('popular.partials.types.' . $post->contentType->icon, [
                    'post' => $post
                ])
            @endforeach
        </div>
        <div class="popular__page-links">

            {{-- Предыдущая --}}
            @if ($posts->onFirstPage())
                <span class="popular__page-link popular__page-link--prev button button--gray button--disabled">
                    Предыдущая страница
                </span>
            @else
                <a class="popular__page-link popular__page-link--prev button button--gray"
                   href="{{ $posts->previousPageUrl() }}">
                    Предыдущая страница
                </a>
            @endif

            {{-- Следующая --}}
            @if ($posts->hasMorePages())
                <a class="popular__page-link popular__page-link--next button button--gray"
                   href="{{ $posts->nextPageUrl() }}">
                    Следующая страница
                </a>
            @else
                <span class="popular__page-link popular__page-link--next button button--gray button--disabled">
                    Следующая страница
                </span>
            @endif

        </div>

      </div>
    </section>
@endsection
