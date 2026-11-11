<section class="adding-post__photo tabs__content {{ $type === 'photo' ? 'tabs__content--active' : '' }}">
    <h2 class="visually-hidden">Форма добавления фото</h2>

    <form class="adding-post__form form"
          action="{{ route('posts.store') }}"
          method="post"
          enctype="multipart/form-data">

        @csrf
        <input type="hidden" name="content_type_id" value="3">

        <div class="form__text-inputs-wrapper">
            <div class="form__text-inputs">

                {{-- Заголовок --}}
                <div class="adding-post__input-wrapper form__input-wrapper">
                    <label class="adding-post__label form__label" for="photo-heading">
                        Заголовок
                    </label>

                    <div class="form__input-section {{ $errors->has('title') ? 'form__input-section--error form__input-section--active' : '' }}">
                        <input class="adding-post__input form__input"
                               id="photo-heading"
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

                {{-- URL картинки --}}
                <div class="adding-post__input-wrapper form__input-wrapper">
                    <label class="adding-post__label form__label" for="photo-url">Ссылка из интернета</label>

                    <div class="form__input-section {{ $errors->has('image_url') ? 'form__input-section--error form__input-section--active' : '' }}">
                        <input class="adding-post__input form__input"
                               id="photo-url"
                               type="text"
                               name="image_url"
                               value="{{ old('image_url') }}"
                               placeholder="Введите ссылку">

                        @error('image_url')
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
                    <label class="adding-post__label form__label" for="photo-tags">Теги</label>

                    <div class="form__input-section {{ $errors->has('tags') ? 'form__input-section--error form__input-section--active' : '' }}">
                        <input class="adding-post__input form__input"
                               id="photo-tags"
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

            {{-- Общий блок ошибок --}}
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

        {{-- Файл --}}
        <div class="adding-post__input-file-container form__input-container form__input-container--file">
            <div class="adding-post__input-file-wrapper form__input-file-wrapper">

                <input id="image_file"
                       type="file"
                       name="image_file"
                       accept="image/*"
                       class="visually-hidden">

                <label for="image_file" class="adding-post__input-file-button form__input-file-button button">
                    <span>Выбрать фото</span>
                    <svg class="adding-post__attach-icon form__attach-icon" width="10" height="20">
                        <use xlink:href="#icon-attach"></use>
                    </svg>
                </label>

                @error('image_file')
                    <div class="form__error-text" style="margin-top: 10px;">
                        <h3 class="form__error-title">Ошибка</h3>
                        <p class="form__error-desc">{{ $message }}</p>
                    </div>
                @enderror
            </div>

            <div class="adding-post__file form__file"></div>
        </div>

        <div class="adding-post__buttons">
            <button class="adding-post__submit button button--main" type="submit">Опубликовать</button>
            <a class="adding-post__close" href="#">Закрыть</a>
        </div>

    </form>
</section>
