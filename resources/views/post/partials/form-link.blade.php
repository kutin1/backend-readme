<section class="adding-post__link tabs__content {{ $type === 'link' ? 'tabs__content--active' : '' }}">
    <h2 class="visually-hidden">Форма добавления ссылки</h2>

    <form class="adding-post__form form" action="{{ route('posts.store') }}" method="post">
        @csrf
        <input type="hidden" name="content_type_id" value="5">

        <div class="form__text-inputs-wrapper">
            <div class="form__text-inputs">

                {{-- Заголовок (не обязателен) --}}
                <div class="adding-post__input-wrapper form__input-wrapper">
                    <label class="adding-post__label form__label" for="link-heading">Заголовок</label>
                    <div class="form__input-section {{ $errors->has('title') ? 'form__input-section--error form__input-section--active' : '' }}">

                        <input class="adding-post__input form__input"
                               id="link-heading"
                               type="text"
                               name="title"
                               value="{{ old('title') }}"
                               placeholder="Введите заголовок">

                        @error('title')
                            <button class="form__error-button button" type="button">!</button>
                            <div class="form__error-text">
                                <h3 class="form__error-title">Ошибка</h3>
                                <p class="form__error-desc">{{ $message }}</p>
                            </div>
                        @enderror

                    </div>
                </div>

                {{-- Ссылка (обязательна) --}}
                <div class="adding-post__input-wrapper form__input-wrapper">
                    <label class="adding-post__label form__label" for="post-link">
                        Ссылка <span class="form__input-required">*</span>
                    </label>
                     <div class="form__input-section {{ $errors->has('link') ? 'form__input-section--error form__input-section--active' : '' }}">
                        <input class="adding-post__input form__input"
                               id="post-link"
                               type="text"
                               name="link"
                               value="{{ old('link') }}"
                               placeholder="Введите ссылку">

                        @error('link')
                            <button class="form__error-button button" type="button">!</button>
                            <div class="form__error-text">
                                <h3 class="form__error-title">Ошибка</h3>
                                <p class="form__error-desc">{{ $message }}</p>
                            </div>
                        @enderror
                    </div>
                </div>

                {{-- Теги --}}
                <div class="adding-post__input-wrapper form__input-wrapper">
                    <label class="adding-post__label form__label" for="link-tags">Теги</label>
                    <div class="form__input-section {{ $errors->has('tags') ? 'form__input-section--error form__input-section--active' : '' }}">

                        <input class="adding-post__input form__input"
                               id="link-tags"
                               type="text"
                               name="tags"
                               value="{{ old('tags') }}"
                               placeholder="Введите теги">

                        @error('tags')
                            <button class="form__error-button button" type="button">!</button>
                            <div class="form__error-text">
                                <h3 class="form__error-title">Ошибка</h3>
                                <p class="form__error-desc">{{ $message }}</p>
                            </div>
                        @enderror

                    </div>
                </div>

            </div>

            {{-- Общий список ошибок --}}
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

        <div class="adding-post__buttons">
            <button class="adding-post__submit button button--main" type="submit">Опубликовать</button>
            <a class="adding-post__close" href="#">Закрыть</a>
        </div>
    </form>
</section>
