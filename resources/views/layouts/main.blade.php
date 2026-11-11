<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>readme: блог, каким он должен быть</title>

    <link rel="stylesheet" href="/build/css/main.css">
</head>

<body class="page page--main">

    {{-- SVG sprite --}}
    @include('partials.sprite')

    {{-- HEADER --}}
    @include('partials.header-main')

    <main>
        @yield('content')
    </main>

    {{-- FOOTER --}}
    @include('partials.footer')

    <script src="/build/js/main.js"></script>
</body>
</html>
