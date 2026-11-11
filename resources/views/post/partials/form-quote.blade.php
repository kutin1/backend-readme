<section class="adding-post__quote tabs__content {{ $type === 'quote' ? 'tabs__content--active' : '' }}">
    <h2 class="visually-hidden">Форма добавления цитаты</h2>

    <form class="adding-post__form form" action="{{ route('posts.store') }}" method="post">
        @csrf
        <input type="hidden" name="content_type_id" value="2">

        <div class="form__text-inputs-wrapper">
            <div class="form__text-inputs">

                {{-- Заголовок (не обязателен) --}}
                <div class="adding-post__input-wrapper form__input-wrapper">
                    <label class="adding-post__label form__label" for="quote-heading">Заголовок</label>
                    <div class="form__input-section {{ $errors->has('title') ? 'form__input-section--error form__input-section--active' : '' }}">

                        <input class="adding-post__input form__input"
                               id="quote-heading"
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

                {{-- Текст цитаты (обязателен) --}}
                <div class="adding-post__textarea-wrapper form__textarea-wrapper">
                    <label class="adding-post__label form__label" for="cite-text">
                        Текст цитаты <span class="form__input-required">*</span>
                    </label>
                    <div class="form__input-section {{ $errors->has('content') ? 'form__input-section--error form__input-section--active' : '' }}">

                        <textarea class="adding-post__textarea adding-post__textarea--quote form__textarea form__input"
                                  id="cite-text"
                                  name="content"
                                  placeholder="Текст цитаты">{{ old('content') }}</textarea>

                        @error('content')
                            <button class="form__error-button button" type="button">!</button>
                            <div class="form__error-text">
                                <h3 class="form__error-title">Ошибка</h3>
                                <p class="form__error-desc">{{ $message }}</p>
                            </div>
                        @enderror

                    </div>
                </div>

                {{-- Автор (обязателен) --}}
                <div class="adding-post__input-wrapper form__input-wrapper">
                    <label class="adding-post__label form__label" for="quote-author">
                        Автор <span class="form__input-required">*</span>
                    </label>
                    <div class="form__input-section {{ $errors->has('quote_author') ? 'form__input-section--error form__input-section--active' : '' }}">

                        <input class="adding-post__input form__input"
                               id="quote-author"
                               type="text"
                               name="quote_author"
                               value="{{ old('quote_author') }}"
                               placeholder="Введите автора">

                        @error('quote_author')
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
                    <label class="adding-post__label form__label" for="cite-tags">Теги</label>
                    <div class="form__input-section {{ $errors->has('tags') ? 'form__input-section--error form__input-section--active' : '' }}">

                        <input class="adding-post__input form__input"
                               id="cite-tags"
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
