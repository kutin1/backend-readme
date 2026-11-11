<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>readme: регистрация</title>

    {{-- CSS --}}
    <link rel="stylesheet" href="/build/css/main.css">
</head>

<body class="page">

    {{-- SVG SPRITE --}}
    <div style="display: none">
        @include('partials.sprite')
    </div>

    {{-- HEADER --}}
    @include('partials.header-guest')

    {{-- MAIN CONTENT --}}
    @yield('content')

    {{-- FOOTER --}}
    @include('partials.footer')

    {{-- JS --}}
    <!-- <script src="/build/libs/dropzone.js"></script>
    <script src="/build/js/dropzone-settings.js"></script> -->
    <script src="/build/js/main.js"></script>

</body>
</html>
