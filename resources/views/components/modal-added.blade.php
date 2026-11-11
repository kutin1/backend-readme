<div class="modal modal--adding modal--active">
  <div class="modal__wrapper">
    <a href="{{ route('feed') }}" class="modal__close-button button">
      <svg class="modal__close-icon" width="18" height="18">
        <use xlink:href="#icon-close"></use>
      </svg>
      <span class="visually-hidden">Закрыть модальное окно</span>
    </a>

    <div class="modal__content">
      <h1 class="modal__title">Пост добавлен</h1>
      <p class="modal__desc">
        Ваш пост успешно опубликован.
      </p>

      <div class="modal__buttons">
        <a class="modal__button button button--main" href="{{ route('feed') }}">Перейти в ленту</a>
        <a class="modal__button button button--gray" href="{{ route('post.create') }}">Добавить ещё</a>
      </div>
    </div>
  </div>
</div>
