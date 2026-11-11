<div class="comments" id="comments">

    {{-- Форма добавления комментария --}}
    <form class="comments__form form"
          action="{{ route('comments.store', $post) }}"
          method="post">
        @csrf

        {{-- Аватар текущего пользователя --}}
        <div class="comments__my-avatar">
            <x-avatar :src="Auth::user()->avatar"
                class="comments__picture"
                alt="Аватар пользователя" />
        </div>

        {{-- Поле ввода комментария --}}
        <div class="form__input-section @error('content') form__input-section--error @enderror">

            <textarea
                id="comment-field"
                class="comments__textarea form__textarea form__input"
                name="content"
                placeholder="Ваш комментарий"
            >{{ old('content') }}</textarea>

            <label class="visually-hidden">Ваш комментарий</label>

            {{-- Ошибка --}}
            @error('content')
                <button class="form__error-button button" type="button">!</button>

                <div class="form__error-text">
                    <h3 class="form__error-title">Ошибка</h3>
                    <p class="form__error-desc">{{ $message }}</p>
                </div>
            @enderror
        </div>

        <button class="comments__submit button button--green" type="submit">
            Отправить
        </button>
    </form>

    {{-- Список комментариев --}}
    <div class="comments__list-wrapper">
        <ul class="comments__list">

            @forelse($post->comments as $comment)
                <li class="comments__item user">

                    {{-- Аватар автора комментария --}}
                    <div class="comments__avatar">
                        <a class="user__avatar-link"
                           href="{{ route('profile.posts', $comment->author) }}">
                            <x-avatar :src="$comment->author->avatar"
                                class="comments__picture"
                                alt="Аватар пользователя" />
                        </a>
                    </div>

                    {{-- Информация о комментарии --}}
                    <div class="comments__info">

                        <div class="comments__name-wrapper">
                            <a class="comments__user-name"
                               href="{{ route('profile.posts', $comment->author) }}">
                                <span>{{ $comment->author->login }}</span>
                            </a>

                            <time class="comments__time"
                                  datetime="{{ $comment->created_at->toDateString() }}">
                                {{ $comment->created_at->diffForHumans() }}
                            </time>
                        </div>

                        <p class="comments__text">
                            {{ $comment->content }}
                        </p>

                    </div>

                </li>
            @empty
                <p class="comments__empty">Комментариев пока нет</p>
            @endforelse

        </ul>

        {{-- Показать все комментарии (если их много) --}}
        @if($post->comments()->count() > 10)
            <a class="comments__more-link" href="#">
                <span>Показать все комментарии</span>
                <sup class="comments__amount">{{ $post->comments()->count() }}</sup>
            </a>
        @endif

    </div>
</div>
