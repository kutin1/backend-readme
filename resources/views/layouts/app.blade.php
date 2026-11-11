<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>readme</title>

    <link rel="stylesheet" href="/build/css/main.css">
</head>

<body class="page">

    {{-- SVG sprite --}}
    @include('partials.sprite')

    {{-- HEADER авторизованного пользователя --}}
    @include('partials.header-auth')

    {{-- Контент страницы --}}
    <main class="page__main">
        @yield('content')
    </main>

    {{-- FOOTER --}}
    @include('partials.footer')

    @if(session('post_added'))
        @include('components.modal-added')
    @endif
    <!-- <script src="/build/libs/dropzone.js"></script>
    <script src="/build/js/dropzone-settings.js"></script> -->
    <script src="/build/js/main.js"></script>
</body>
</html>
