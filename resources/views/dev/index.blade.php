<!DOCTYPE html>
<html lang="ru">
  <head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>readme: блог, каким он должен быть</title>
    <link rel="stylesheet" href="build/css/main.css">
  </head>
  <body>
    <ul>
      <li>
        <a href="{{ route('main') }}">Главная</a>
      </li>
      <li>
        <a href="{{ route('login') }}">Вход</a>
      </li>
      <li>
        <a href="{{ route('feed') }}">Моя лента</a>
      </li>
      <li>
        <a href="#"><del>[Демонстрация] Моя лента (нет публикаций)</del></a>
        <i>(учтено при реализации проекта)</i>
      </li>
      <li>
        <a href="{{ route('messages.index') }}">Личные сообщения</a>
      </li>
      <li>
        <a href="#"><del>Результаты поиска (нет результатов)</del></a>
        <i>(учтено при реализации проекта)</i>
      </li>
      <li>
        <a href="{{ route('popular.index') }}">Популярное</a>
      </li>
      <li>
        <a href="#"><del>Публикация</del></a>
        <i>(страница открывается только по ID поста)</i>
      </li>
      <li>
        <a href="{{ auth()->check() ? route('profile.posts', auth()->id()) : route('login') }}">Профиль</a>
      </li>
      <li>
        <a href="{{ route('search', ['q' => 'текст']) }}">Результаты поиска</a>
        <i>(искомый запрос указывается в параметре q)</i>
      </li>
      <li>
        <a href="{{ route('register.show') }}">Регистрация</a>
      </li>
      <li>
        <a href="{{ route('post.create') }}">Форма добавления поста</a>
      </li>
      <li>
        <a href="#"><del>[Демонстрация] Модальное окно</del></a>
        <i>(учтено при реализации проекта)</i>
      </li>
      <li>
        <a href="#"><del>[Демонстрация] Непройденная валидация (форма регистрации)</del></a>
        <i>(учтено при реализации проекта)</i>
      </li>
      <li>
        <a href="#"><del>[Демонстрация] Непройденная валидация (форма авторизации на главной)</del></a>
        <i>(учтено при реализации проекта)</i>
      </li>
    </ul>
  </body>
</html>
