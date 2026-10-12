<!doctype html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title','ДПО') · ZMUDPO</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body>
<div id="ajax-progress"></div>
<div class="app-shell">
    <aside class="sidebar">
        <a class="brand text-decoration-none" href="{{ route('dashboard') }}">
            <span class="brand-mark">Z</span>
            <span><strong>ZMUDPO</strong><small>ДПО / НМО</small></span>
        </a>

        <nav class="app-nav">
            <div class="nav-caption">Обучение</div>
            <a class="nav-link" href="{{route('dashboard')}}"><i class="bi bi-grid"></i><span>Мой кабинет</span></a>
            <a class="nav-link" href="{{route('learning.index')}}"><i class="bi bi-mortarboard"></i><span>Обучение</span></a>
            <a class="nav-link" href="{{route('quizzes.index')}}"><i class="bi bi-ui-checks"></i><span>Тестирование</span></a>
            <a class="nav-link" href="{{route('practice.index')}}"><i class="bi bi-journal-check"></i><span>Практика</span></a>
            <a class="nav-link" href="{{route('final-works.index')}}"><i class="bi bi-award"></i><span>Итоговые работы</span></a>

            <div class="nav-caption">Сервисы</div>
            <a class="nav-link" href="{{route('student.schedule')}}"><i class="bi bi-calendar3"></i><span>Расписание</span></a>
            <a class="nav-link" href="{{route('student.messages')}}"><i class="bi bi-chat-dots"></i><span>Куратор</span></a>
            <a class="nav-link" href="{{route('student.surveys')}}"><i class="bi bi-card-checklist"></i><span>Опросы</span></a>
            <a class="nav-link" href="{{route('student.resources')}}"><i class="bi bi-collection"></i><span>Библиотека</span></a>
            <a class="nav-link" href="{{route('documents.index')}}"><i class="bi bi-file-earmark-text"></i><span>Документы</span></a>
            <a class="nav-link" href="{{route('history.index')}}"><i class="bi bi-clock-history"></i><span>История</span></a>
            <a class="nav-link" href="{{route('profile.show')}}"><i class="bi bi-person"></i><span>Профиль</span></a>
            @if(auth()->user()->is_legal_entity)<a class="nav-link" href="{{route('organization.index')}}">Моя организация</a>@endif

            @if(auth()->user()->role==='curator')
                <div class="nav-caption">Куратор</div>
                <a class="nav-link" href="{{route('curator.dashboard')}}"><i class="bi bi-speedometer2"></i><span>Кабинет куратора</span></a>
                <a class="nav-link" href="{{route('curator.students')}}"><i class="bi bi-people"></i><span>Слушатели</span></a>
                <a class="nav-link" href="{{route('curator.review')}}"><i class="bi bi-check2-square"></i><span>Проверка работ</span></a>
                <a class="nav-link" href="{{route('curator.messages')}}"><i class="bi bi-chat-square-text"></i><span>Сообщения</span></a>
                <a class="nav-link" href="{{route('curator.attendance')}}"><i class="bi bi-activity"></i><span>Посещаемость</span></a>
                <a class="nav-link" href="{{route('curator.content')}}"><i class="bi bi-activity"></i><span>Контроль NMO</span></a>
            @endif

            @if(auth()->user()->role==='admin')
                <div class="nav-caption">Администрирование</div>
                <a class="nav-link nav-primary" href="{{route('admin.dashboard')}}"><i class="bi bi-sliders"></i><span>Панель управления</span></a>
                <a class="nav-link nav-primary" href="{{route('admin.nmo.index')}}"><i class="bi bi-diagram-3"></i><span>Конструктор НМО/ДПО</span></a>
                <a class="nav-link" href="{{route('admin.users.index')}}"><i class="bi bi-person-gear"></i><span>Пользователи</span></a>
                <a class="nav-link" href="{{route('admin.organizations.index')}}"><i class="bi bi-building"></i><span>Организации</span></a>
                <a class="nav-link" href="{{route('admin.programs.index')}}"><i class="bi bi-journals"></i><span>Программы</span></a>
                <a class="nav-link" href="{{route('admin.groups.index')}}"><i class="bi bi-people-fill"></i><span>Группы</span></a>
                <a class="nav-link" href="{{route('admin.enrollments.index')}}"><i class="bi bi-person-check"></i><span>Назначения</span></a>
                <a class="nav-link" href="{{route('admin.quizzes.index')}}"><i class="bi bi-patch-question"></i><span>Тесты</span></a>
                <a class="nav-link" href="{{route('admin.documents.index')}}"><i class="bi bi-file-earmark-medical"></i><span>Документы</span></a>
                <a class="nav-link" href="{{route('admin.document-templates.index')}}"><i class="bi bi-layout-text-window"></i><span>Шаблоны документов</span></a>
                <a class="nav-link" href="{{route('admin.reports.index')}}"><i class="bi bi-printer"></i><span>Отчёты</span></a>
                <a class="nav-link" href="{{route('admin.announcements.index')}}"><i class="bi bi-megaphone"></i><span>Объявления</span></a>
                <a class="nav-link" href="{{route('admin.legacy.index')}}"><i class="bi bi-tools"></i><span>Все legacy-функции</span></a>
            @endif
        </nav>

        <form class="sidebar-logout" method="post" action="{{route('logout')}}" data-no-ajax>
            @csrf
            <button class="btn btn-outline-light w-100"><i class="bi bi-box-arrow-left me-2"></i>Выйти</button>
        </form>
    </aside>

    <div class="main">
        <header class="topbar">
            <button class="menu-button" data-sidebar-toggle aria-label="Меню"><i class="bi bi-list"></i></button>
            <div>
                <div class="top-title">@hasSection('page-title')@yield('page-title')@else @yield('title','Личный кабинет') @endif</div>
                <div class="top-subtitle">ГАПОУ «Зеленодольский медицинский колледж»</div>
            </div>
            <div class="userbox">
                <div><strong>{{auth()->user()->full_name}}</strong><small>{{ auth()->user()->role }}</small></div>
                <a class="user-avatar" href="{{ route('profile.show') }}">{{ mb_strtoupper(mb_substr(auth()->user()->full_name ?: 'П',0,1)) }}</a>
            </div>
        </header>

        <main class="content" data-ajax-root>
            @if(session('ok'))<div class="alert alert-success alert-dismissible fade show">{{session('ok')}}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>@endif
            @if(session('success'))<div class="alert alert-success alert-dismissible fade show">{{session('success')}}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>@endif
            @if($errors->any())<div class="alert alert-danger"><strong>Проверьте данные:</strong><ul class="mb-0 mt-1">@foreach($errors->all() as $e)<li>{{$e}}</li>@endforeach</ul></div>@endif
            @yield('content')
        </main>
    </div>
</div>
<div class="sidebar-backdrop" data-sidebar-toggle></div>
<div id="ajax-toast-host" class="toast-container position-fixed top-0 end-0 p-3"></div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="{{ asset('js/ajax-app.js') }}"></script>
@stack('scripts')
</body>
</html>
