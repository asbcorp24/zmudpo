@extends('layouts.app')
@section('title','Контроль NMO')
@section('content')
@include('curator._legacy_nav')
<div class="page-heading"><div><h1>Контроль NMO</h1><p>Все действия слушателей по закреплённым программам. Только просмотр и проверка — структуру обучения меняет суперадмин.</p></div></div>

<div class="stats-grid mb-4">
 <div class="stat-card"><span>Ответов / файлов</span><strong>{{$responses->count()}}</strong></div>
 <div class="stat-card"><span>Попыток тестов</span><strong>{{$attempts->count()}}</strong></div>
 <div class="stat-card"><span>Отметок присутствия</span><strong>{{$visits->count()}}</strong></div>
 <div class="stat-card"><span>Выдано билетов</span><strong>{{$tickets->count()}}</strong></div>
 <div class="stat-card"><span>Записей практики</span><strong>{{$diary->count()}}</strong></div>
 <div class="stat-card"><span>Прочтено объявлений</span><strong>{{$acks->count()}}</strong></div>
</div>

<div class="panel mb-4"><div class="panel-head"><h2>Разделы и материалы</h2></div>
@forelse($sections as $section)
 <details class="mb-3"><summary><strong>{{$section->program?->title}} → {{$section->title}}</strong> <span class="badge-soft">{{$section->contentItems->count()}} материалов</span></summary>
 <div class="table-responsive mt-2"><table class="table table-sm align-middle"><thead><tr><th>Порядок</th><th>Тип</th><th>Материал</th><th>Обязательный</th><th>Доступ</th></tr></thead><tbody>
 @foreach($section->contentItems as $item)<tr><td>{{$item->position}}</td><td>tip {{$item->legacy_type}}</td><td>{{$item->title}}</td><td>{{$item->is_required?'да':'—'}}</td><td>@if($item->available_from)с {{$item->available_from->format('d.m.Y H:i')}}@endif @if($item->available_until) до {{$item->available_until->format('d.m.Y H:i')}}@endif</td></tr>@endforeach
 </tbody></table></div></details>
@empty<p class="empty">Разделов нет.</p>@endforelse
</div>

<div class="panel mb-4"><div class="panel-head"><h2>Ответы, файлы и рабочие тетради</h2><a href="{{route('curator.review')}}">Открыть проверку работ →</a></div>
<div class="table-responsive"><table class="table table-hover align-middle"><thead><tr><th>Дата</th><th>Слушатель</th><th>Раздел</th><th>Материал</th><th>Тип</th><th>Статус</th><th>Оценка</th></tr></thead><tbody>
@forelse($responses as $x)<tr><td>{{$x->submitted_at?->format('d.m.Y H:i')}}</td><td>{{$x->user?->full_name}}</td><td>{{$x->item?->section?->title}}</td><td>{{$x->item?->title}}</td><td>tip {{$x->item?->legacy_type}}</td><td><span class="status {{$x->status==='accepted'?'completed':($x->status==='submitted'?'active':'blocked')}}">{{$x->status}}</span></td><td>{{$x->score??'—'}}</td></tr>@empty<tr><td colspan="7">Нет действий.</td></tr>@endforelse
</tbody></table></div></div>

<div class="panel mb-4"><div class="panel-head"><h2>Попытки тестов</h2></div><div class="table-responsive"><table class="table table-hover align-middle"><thead><tr><th>Дата</th><th>Слушатель</th><th>Тест</th><th>Результат</th><th>Статус</th></tr></thead><tbody>
@forelse($attempts as $x)<tr><td>{{$x->finished_at?->format('d.m.Y H:i')}}</td><td>{{$x->user?->full_name}}</td><td>{{$x->assignment?->title}}</td><td>{{number_format((float)$x->percent,1)}}%</td><td>{{$x->passed?'пройден':'не пройден'}}</td></tr>@empty<tr><td colspan="5">Попыток нет.</td></tr>@endforelse
</tbody></table></div></div>

<div class="panel mb-4"><div class="panel-head"><h2>Присутствие в разделах</h2></div><div class="table-responsive"><table class="table table-hover"><thead><tr><th>Дата</th><th>Слушатель</th><th>Программа</th><th>Раздел</th></tr></thead><tbody>
@forelse($visits as $x)<tr><td>{{$x->visited_at?->format('d.m.Y H:i:s')}}</td><td>{{$x->user?->full_name}}</td><td>{{$x->section?->program?->title}}</td><td>{{$x->section?->title}}</td></tr>@empty<tr><td colspan="4">Отметок нет.</td></tr>@endforelse
</tbody></table></div></div>

<div class="panel mb-4"><div class="panel-head"><h2>Билеты tip=20</h2></div><div class="table-responsive"><table class="table table-hover"><thead><tr><th>Дата</th><th>Слушатель</th><th>Раздел</th><th>Материал</th><th>Билет</th><th>Область уникальности</th></tr></thead><tbody>
@forelse($tickets as $x)<tr><td>{{$x->created_at?->format('d.m.Y H:i')}}</td><td>{{$x->user?->full_name}}</td><td>{{$x->item?->section?->title}}</td><td>{{$x->item?->title}}</td><td><strong>{{$x->ticket_number}}</strong></td><td>{{$x->scope_key}}</td></tr>@empty<tr><td colspan="6">Билетов нет.</td></tr>@endforelse
</tbody></table></div></div>

<div class="panel mb-4"><div class="panel-head"><h2>Дневники практики tip=16</h2></div><div class="table-responsive"><table class="table table-hover"><thead><tr><th>Дата работы</th><th>Слушатель</th><th>Раздел</th><th>Работа</th><th>Руководитель</th></tr></thead><tbody>
@forelse($diary as $x)<tr><td>{{$x->work_date?->format('d.m.Y')}}</td><td>{{$x->user?->full_name}}</td><td>{{$x->item?->section?->title}}</td><td>{{$x->work_done}}</td><td>{{$x->supervisor??'—'}}</td></tr>@empty<tr><td colspan="5">Записей нет.</td></tr>@endforelse
</tbody></table></div></div>

<div class="panel"><div class="panel-head"><h2>Прочтение объявлений</h2></div><div class="table-responsive"><table class="table table-hover"><thead><tr><th>Дата</th><th>Слушатель</th><th>Объявление</th></tr></thead><tbody>
@forelse($acks as $x)<tr><td>{{$x->acknowledged_at?->format('d.m.Y H:i')}}</td><td>{{$x->user?->full_name}}</td><td>{{$x->announcement?->title}}</td></tr>@empty<tr><td colspan="3">Подтверждений нет.</td></tr>@endforelse
</tbody></table></div></div>
@endsection