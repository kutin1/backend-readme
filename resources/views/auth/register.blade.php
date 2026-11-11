@extends('layouts.guest')

@section('content')
    <main class="page__main page__main--registration">
        <div class="container">
          <h1 class="page__title page__title--registration">Регистрация</h1>
        </div>
        <section class="registration container">
          <h2 class="visually-hidden">Форма регистрации</h2>
          <form class="registration__form form" action="{{ route('register.perform') }}" method="post" enctype="multipart/form-data">
            @csrf
            <div class="form__text-inputs-wrapper">
              <div class="form__text-inputs">
                <div class="registration__input-wrapper form__input-wrapper">
                  <label class="registration__label form__label" for="registration-email">Электронная почта <span class="form__input-required">*</span></label>
                  <div class="form__input-section {{ $errors->has('email') ? 'form__input-section--error form__input-section--active' : '' }}">
                    <input
                        class="registration__input form__input"
                        id="registration-email"
                        type="text"
                        name="email"
                        value="{{ old('email') }}"
                        placeholder="Укажите эл.почту"
                    >

                    <button class="form__error-button button" type="button">!
                        <span class="visually-hidden">Информация об ошибке</span>
                    </button>

                    <div class="form__error-text">
                        <h3 class="form__error-title">Ошибка</h3>
                        <p class="form__error-desc">
                            @error('email') {{ $message }} @enderror
                        </p>
                    </div>
                  </div>
                </div>
                <div class="registration__input-wrapper form__input-wrapper">
                    <label class="registration__label form__label" for="registration-login">
                        Логин <span class="form__input-required">*</span>
                    </label>

                    <div class="form__input-section {{ $errors->has('login') ? 'form__input-section--error form__input-section--active' : '' }}">
                        <input
                            class="registration__input form__input"
                            id="registration-login"
                            type="text"
                            name="login"
                            value="{{ old('login') }}"
                            placeholder="Укажите логин"
                        >

                        <button class="form__error-button button" type="button">!
                            <span class="visually-hidden">Информация об ошибке</span>
                        </button>

                        <div class="form__error-text">
                            <h3 class="form__error-title">Ошибка</h3>
                            <p class="form__error-desc">
                                @error('login') {{ $message }} @enderror
                            </p>
                        </div>
                      </div>
                    </div>

                    <div class="registration__input-wrapper form__input-wrapper">
                    <label class="registration__label form__label" for="registration-password">
                        Пароль <span class="form__input-required">*</span>
                    </label>

                    <div class="form__input-section {{ $errors->has('password') ? 'form__input-section--error form__input-section--active' : '' }}">
                        <input
                            class="registration__input form__input"
                            id="registration-password"
                            type="password"
                            name="password"
                            placeholder="Придумайте пароль"
                        >

                        <button class="form__error-button button" type="button">!
                            <span class="visually-hidden">Информация об ошибке</span>
                        </button>

                        <div class="form__error-text">
                            <h3 class="form__error-title">Ошибка</h3>
                            <p class="form__error-desc">
                                @error('password') {{ $message }} @enderror
                            </p>
                        </div>
                    </div>
                </div>

                <div class="registration__input-wrapper form__input-wrapper">
                <label class="registration__label form__label" for="registration-password-repeat">
                    Повтор пароля <span class="form__input-required">*</span>
                </label>

                <div class="form__input-section {{ $errors->has('password-repeat') ? 'form__input-section--error form__input-section--active' : '' }}">
                    <input
                        class="registration__input form__input"
                        id="registration-password-repeat"
                        type="password"
                        name="password-repeat"
                        placeholder="Повторите пароль"
                    >

                    <button class="form__error-button button" type="button">!
                        <span class="visually-hidden">Информация об ошибке</span>
                    </button>

                    <div class="form__error-text">
                        <h3 class="form__error-title">Ошибка</h3>
                        <p class="form__error-desc">
                            @error('password-repeat') {{ $message }} @enderror
                        </p>
                    </div>
                  </div>
                </div>

            </div>
            @if ($errors->any())
                <div class="form__invalid-block">
                    <b class="form__invalid-slogan">Пожалуйста, исправьте следующие ошибки:</b>
                    <ul class="form__invalid-list">
                        @foreach ($errors->all() as $error)
                            <li class="form__invalid-item">{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            </div>
            <div class="registration__input-file-container form__input-container form__input-container--file">
                <div class="registration__input-file-wrapper form__input-file-wrapper">

                    <!-- Скрытый input -->
                    <input
                        id="avatar"
                        type="file"
                        name="avatar"
                        class="visually-hidden"
                    >

                    <!-- Кнопка выбора файла -->
                    <label for="avatar" class="registration__input-file-button form__input-file-button button">
                        <span>Выбрать фото</span>
                        <svg class="registration__attach-icon form__attach-icon" width="10" height="20">
                            <use xlink:href="#icon-attach"></use>
                        </svg>
                    </label>

                </div>

                <div class="registration__file form__file"></div>
            </div>

            <button class="registration__submit button button--main" type="submit">Отправить</button>
          </form>
        </section>
    </main>
@endsection
