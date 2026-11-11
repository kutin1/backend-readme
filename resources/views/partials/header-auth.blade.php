<header class="header">
  <div class="header__wrapper container">
    <div class="header__logo-wrapper">
      <a class="header__logo-link" href="{{ route('feed') }}">
        <img class="header__logo" src="/build/img/logo.svg" alt="Логотип readme" width="128" height="24">
      </a>
      <p class="header__topic">
        micro blogging
      </p>
    </div>
    <form class="header__search-form form" action="{{ route('search') }}" method="get">
        <div class="header__search">
            <label class="visually-hidden">Поиск</label>
            <input class="header__search-input form__input" type="search" name="q" value="{{ request('q') }}">
            <button class="header__search-button button" type="submit">
                <svg class="header__search-icon" width="18" height="18">
                    <use xlink:href="#icon-search"></use>
                </svg>
                <span class="visually-hidden">Начать поиск</span>
            </button>
        </div>
    </form>
    <div class="header__nav-wrapper">
      <nav class="header__nav">
        <ul class="header__my-nav">
          <li class="header__my-page header__my-page--popular">
            <a class="header__page-link {{ Route::is('popular.index') ? 'header__page-link--active' : '' }}"
                href="{{ route('popular.index') }}"
                title="Популярный контент">
              <span class="visually-hidden">Популярный контент</span>
            </a>
          </li>
          <li class="header__my-page header__my-page--feed">
            <a class="header__page-link {{ Route::is('feed') ? 'header__page-link--active' : '' }}"
                href="{{ route('feed') }}"
                title="Моя лента">
              <span class="visually-hidden">Моя лента</span>
            </a>
          </li>
          <li class="header__my-page header__my-page--messages">
            <a class="header__page-link {{ Route::is('messages.*') ? 'header__page-link--active' : '' }}"
                href="{{ route('messages.index') }}"
                title="Личные сообщения">
              <span class="visually-hidden">Личные сообщения</span>
            </a>
          </li>
        </ul>
        <ul class="header__user-nav">
          <li class="header__profile">
            <a class="header__profile-link {{ Route::is('profile.*') ? 'header__page-link--active' : '' }}"
                href="{{ route('profile.posts', Auth::id()) }}">
              <div class="header__avatar-wrapper">
                <x-avatar :src="Auth::user()->avatar"
                    class="header__profile-avatar"
                    alt="Аватар профиля {{ Auth::user()->login }}" />
              </div>
              <div class="header__profile-name">
                <span>{{ Auth::user()->login }}</span>
                <svg class="header__link-arrow" width="10" height="6">
                  <use xlink:href="#icon-arrow-right-ad"></use>
                </svg>
              </div>
            </a>
            <div class="header__tooltip-wrapper">
              <div class="header__profile-tooltip">
                <ul class="header__profile-nav">
                  <li class="header__profile-nav-item">
                    <a class="header__profile-nav-link" href="{{ route('profile.posts', auth()->id()) }}">
                      <span class="header__profile-nav-text">
                        Мой профиль
                      </span>
                    </a>
                  </li>
                  <li class="header__profile-nav-item">
                    <a class="header__profile-nav-link" href="{{ route('messages.index') }}">
                      <span class="header__profile-nav-text">
                        Сообщения
                        @if($unreadMessages > 0)
                            <i class="header__profile-indicator">{{ $unreadMessages }}</i>
                        @endif
                      </span>
                    </a>
                  </li>
                  <li class="header__profile-nav-item">
                    <a class="header__profile-nav-link" href="#">
                      <span class="header__profile-nav-text">
                        Настройки
                      </span>
                    </a>
                  </li>
                  <li class="header__profile-nav-item">
                    <form action="{{ route('logout') }}" method="POST" style="display: contents;">
                        @csrf
                        <button type="submit" class="header__profile-nav-link" style="background: none; border: none; padding: 0; cursor: pointer;">
                            <span class="header__profile-nav-text">Выход</span>
                        </button>
                    </form>
                  </li>
                </ul>
              </div>
            </div>
          </li>
          <li>
            <a class="header__post-button button button--transparent" href="{{ route('post.create') }}">Пост</a>
          </li>
        </ul>
      </nav>
    </div>
  </div>
</header>
