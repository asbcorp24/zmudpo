@extends('layouts.app')
@section('title','Обучение')
@section('page-title','Учебные материалы')
@section('content')
<div class="page-heading"><div><h1>Обучение</h1><p>Разделы ДПО и НМО, методические материалы и контроль прохождения.</p></div></div>
<div class="card-grid">@forelse($sections as $section) @php($sp=$progress->get($section->id))
@if($section->can_open)<a class="course-card" href="{{ route('learning.show',$section) }}">@else<div class="course-card" style="opacity:.65">@endif
<span class="course-type">{{ strtoupper($section->type ?? 'раздел') }}</span><h3>{{ $section->title }}</h3><p>{{ $section->program->title }}</p><small>{{ number_format((float)($sp?->progress_percent??0),0) }}% · {{ $section->is_required?'обязательный':'дополнительный' }}</small><span class="link-arrow">{{ $section->can_open?'Открыть →':'Пока недоступен' }}</span>
@if($section->can_open)</a>@else</div>@endif
@empty<div class="panel empty">Нет доступных учебных разделов.</div>@endforelse</div>
@endsection
