@extends('layouts.app')

@section('content')
    <section class="page__main page__main--adding-post">
        <div class="page__main-section">
            <div class="container">
                <h1 class="page__title page__title--adding-post">Добавить публикацию</h1>
            </div>

            <div class="adding-post container">

                {{-- вкладки --}}
                @include('post.partials.tabs', ['type' => $type])

                <div class="adding-post__tab-content">

                    @if ($type === 'photo')
                        @include('post.partials.form-photo')
                    @endif

                    @if ($type === 'video')
                        @include('post.partials.form-video')
                    @endif

                    @if ($type === 'text')
                        @include('post.partials.form-text')
                    @endif

                    @if ($type === 'quote')
                        @include('post.partials.form-quote')
                    @endif

                    @if ($type === 'link')
                        @include('post.partials.form-link')
                    @endif

                </div>

            </div>
        </div>
    </section>
@endsection
