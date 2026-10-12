<!doctype html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title','Дополнительное профессиональное образование')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/public-site.css') }}">
</head>
<body>
<div id="ajax-progress"></div>
<header class="site-header">
    <div class="container d-flex align-items-center justify-content-between py-3">
        <a class="site-brand" href="{{route('home')}}">
            <span class="brand-badge">ЗМК</span>
            <span><strong>Дополнительное образование</strong><small>ГАПОУ «Зеленодольский медицинский колледж»</small></span>
        </a>
        <nav class="d-none d-lg-flex align-items-center gap-4">
            <a href="{{route('public.programs')}}">Программы</a>
            <a href="{{route('home')}}#news" data-no-ajax>Новости</a>
            <a href="{{route('home')}}#about" data-no-ajax>Об отделении</a>
            <a href="{{route('home')}}#contacts" data-no-ajax>Контакты</a>
        </nav>
        <div class="d-flex gap-2">
            @auth<a class="btn btn-outline-primary" href="{{route('dashboard')}}">Личный кабинет</a>
            @else<a class="btn btn-outline-primary" href="{{route('login')}}" data-no-ajax>Войти</a>@endauth
            <a class="btn btn-primary d-none d-md-inline-flex" href="{{route('public.programs')}}">Выбрать программу</a>
        </div>
    </div>
</header>

<main data-ajax-root>
    @yield('content')
</main>

<footer class="site-footer" id="contacts">
    <div class="container py-5">
        <div class="row g-4">
            <div class="col-lg-6"><h4>Отделение дополнительного профессионального образования</h4><p>Современное повышение квалификации и непрерывное профессиональное развитие медицинских работников.</p></div>
            <div class="col-lg-3"><h6>Навигация</h6><a href="{{route('public.programs')}}">Программы</a><a href="{{route('login')}}" data-no-ajax>Вход в кабинет</a></div>
            <div class="col-lg-3"><h6>Обучение</h6><p>ДПО · НМО · практические циклы · итоговая аттестация</p></div>
        </div>
    </div>
</footer>
<div id="ajax-toast-host" class="toast-container position-fixed top-0 end-0 p-3"></div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="{{ asset('js/ajax-app.js') }}"></script>
@stack('scripts')
</body>
</html>
