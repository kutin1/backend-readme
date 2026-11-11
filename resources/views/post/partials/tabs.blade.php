<div class="adding-post__tabs-wrapper tabs">
    <div class="adding-post__tabs filters">
        <ul class="adding-post__tabs-list filters__list tabs__list">

            {{-- Фото --}}
            <li class="adding-post__tabs-item filters__item">
                <a href="{{ route('post.create', ['type' => 'photo']) }}"
                   class="adding-post__tabs-link filters__button filters__button--photo
                          {{ $type === 'photo' ? 'filters__button--active' : '' }}">
                    <svg class="filters__icon" width="22" height="18">
                        <use xlink:href="#icon-filter-photo"></use>
                    </svg>
                    <span>Фото</span>
                </a>
            </li>

            {{-- Видео --}}
            <li class="adding-post__tabs-item filters__item">
                <a href="{{ route('post.create', ['type' => 'video']) }}"
                   class="adding-post__tabs-link filters__button filters__button--video
                          {{ $type === 'video' ? 'filters__button--active' : '' }}">
                    <svg class="filters__icon" width="24" height="16">
                        <use xlink:href="#icon-filter-video"></use>
                    </svg>
                    <span>Видео</span>
                </a>
            </li>

            {{-- Текст --}}
            <li class="adding-post__tabs-item filters__item">
                <a href="{{ route('post.create', ['type' => 'text']) }}"
                   class="adding-post__tabs-link filters__button filters__button--text
                          {{ $type === 'text' ? 'filters__button--active' : '' }}">
                    <svg class="filters__icon" width="20" height="21">
                        <use xlink:href="#icon-filter-text"></use>
                    </svg>
                    <span>Текст</span>
                </a>
            </li>

            {{-- Цитата --}}
            <li class="adding-post__tabs-item filters__item">
                <a href="{{ route('post.create', ['type' => 'quote']) }}"
                   class="adding-post__tabs-link filters__button filters__button--quote
                          {{ $type === 'quote' ? 'filters__button--active' : '' }}">
                    <svg class="filters__icon" width="21" height="20">
                        <use xlink:href="#icon-filter-quote"></use>
                    </svg>
                    <span>Цитата</span>
                </a>
            </li>

            {{-- Ссылка --}}
            <li class="adding-post__tabs-item filters__item">
                <a href="{{ route('post.create', ['type' => 'link']) }}"
                   class="adding-post__tabs-link filters__button filters__button--link
                          {{ $type === 'link' ? 'filters__button--active' : '' }}">
                    <svg class="filters__icon" width="21" height="18">
                        <use xlink:href="#icon-filter-link"></use>
                    </svg>
                    <span>Ссылка</span>
                </a>
            </li>

        </ul>
    </div>
</div>
