@extends('layouts.app')
@section('title','Администрирование')
@section('content')
<div class="page-heading"><div><h1>Панель суперадминистратора</h1><p>Создание и редактирование структуры обучения, пользователей и документов. Контроль действий слушателей находится в кабинете куратора.</p></div></div>
<div class="grid">
 <div class="card stat"><span>Пользователи</span><b>{{$users}}</b></div>
 <div class="card stat"><span>Активные программы</span><b>{{$programs}}</b></div>
 <div class="card stat"><span>Активные назначения</span><b>{{$active}}</b></div>
 <div class="card stat"><span>Завершённые назначения</span><b>{{$completed}}</b></div>
 <div class="card stat"><span>Документы</span><b>{{$documents}}</b></div>
</div>
<div class="panel mt-4"><div class="panel-head"><h2>Конструктор</h2></div><div class="d-flex gap-2 flex-wrap">
<a class="btn btn-primary" href="{{route('admin.nmo.index')}}">Конструктор НМО/ДПО</a>
<a class="btn btn-outline-primary" href="{{route('admin.programs.index')}}">Программы</a>
<a class="btn btn-outline-primary" href="{{route('admin.quizzes.index')}}">Тесты</a>
<a class="btn btn-outline-primary" href="{{route('admin.document-templates.index')}}">Шаблоны документов</a>
</div></div>
@endsection