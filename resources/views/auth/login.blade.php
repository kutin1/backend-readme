@extends('layouts.guest')

@section('content')
    <main class="page__main page__main--login">
      <div class="container">
        <h1 class="page__title page__title--login">Вход</h1>
      </div>
      <section class="login container">
        <h2 class="visually-hidden">Форма авторизации</h2>
        <form class="login__form form" action="{{ route('login.perform') }}" method="post">
          @csrf
          <div class="form__text-inputs-wrapper">
            <div class="form__text-inputs">
              <div class="login__input-wrapper form__input-wrapper">
                <label class="login__label form__label" for="login-email">Электронная почта <span class="form__input-required">*</span></label>
                <div class="form__input-section {{ $errors->has('email') ? 'form__input-section--error form__input-section--active' : '' }}">
                    <input
                        class="login__input form__input"
                        id="login-email"
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

              <div class="login__input-wrapper form__input-wrapper">
                <label class="login__label form__label" for="login-password">
                    Пароль <span class="form__input-required">*</span>
                </label>
              <div class="form__input-section {{ $errors->has('password') ? 'form__input-section--error form__input-section--active' : '' }}">
                <input
                    class="login__input form__input"
                    id="login-password"
                    type="password"
                    name="password"
                    placeholder="Введите пароль"
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
          <button class="login__submit button button--main" type="submit">Отправить</button>
        </form>
      </section>
    </main>
@endsection
