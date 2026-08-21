@extends('layouts.app')
@section('title','Куратор')
@section('page-title','Кабинет куратора')
@section('content')
@include('curator._legacy_nav')
<div class="page-heading"><div><h1>Успеваемость</h1><p>Главный экран старого кабинета: слушатели, прогресс, входы и работы.</p></div><a class="btn" href="{{route('curator.students')}}">Все слушатели</a></div>
<div class="stats-grid"><div class="stat-card"><span>Слушателей</span><strong>{{ $students }}</strong></div><div class="stat-card"><span>Активных обучений</span><strong>{{ $activeEnrollments }}</strong></div><div class="stat-card"><span>Работ на проверке</span><strong>{{ $pendingSubmissions }}</strong></div></div>
<div class="panel mt-4"><div class="panel-head"><h2>Последние отправленные работы</h2><a href="{{route('curator.review')}}">Проверка работ →</a></div>@forelse($recentSubmissions as $s)<div class="list-row"><div><strong>{{ $s->user?->full_name }}</strong><small>{{ $s->submitted_at?->format('d.m.Y H:i') }}</small></div><span class="status">{{ $s->status }}</span></div>@empty<p class="empty">Работ на проверке нет.</p>@endforelse</div>
@endsection