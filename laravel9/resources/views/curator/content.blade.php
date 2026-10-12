@extends('layouts.app')
@section('title','Контроль NMO')
@section('content')
@include('curator._legacy_nav')
<div class="page-heading"><div><h1>Контроль NMO</h1><p>Все действия слушателей по закреплённым программам. Только просмотр и проверка — структуру обучения меняет суперадмин.</p></div></div>

<div class="panel mb-4">
<form method="get" class="grid">
<div><label>Программа</label><select class="form-select" name="program_id"><option value="">Все</option>@foreach($programs as $p)<option value="{{$p->id}}" @selected((int)request('program_id')===$p->id)>{{$p->title}}</option>@endforeach</select></div>
<div><label>Группа</label><select class="form-select" name="group_id"><option value="">Все</option>@foreach($groups as $g)<option value="{{$g->id}}" @selected((int)request('group_id')===$g->id)>{{$g->name}}</option>@endforeach</select></div>
<div><label>Слушатель</label><select class="form-select" name="user_id"><option value="">Все</option>@foreach($students as $s)<option value="{{$s->id}}" @selected((int)request('user_id')===$s->id)>{{$s->full_name}}</option>@endforeach</select></div>
<div><label>Действие</label><select class="form-select" name="action"><option value="">Все</option>@foreach($actions as $a)<option value="{{$a->action}}" @selected(request('action')===$a->action)>{{$a->label?:$a->action}}</option>@endforeach</select></div>
<div><label>С даты</label><input class="form-control" type="date" name="date_from" value="{{request('date_from')}}"></div>
<div><label>По дату</label><input class="form-control" type="date" name="date_to" value="{{request('date_to')}}"></div>
<div class="d-flex align-items-end gap-2"><button class="btn btn-primary">Применить</button><a class="btn btn-outline-secondary" href="{{route('curator.content')}}">Сбросить</a></div>
</form></div>

<div class="panel mb-4"><div class="panel-head"><h2>Хронология всех действий</h2><span class="badge-soft">{{$activity->total()}}</span></div>
<div class="table-responsive"><table class="table table-hover align-middle"><thead><tr><th>Дата</th><th>Слушатель</th><th>Действие</th><th>Программа</th><th>Раздел</th><th>Материал</th><th>IP</th><th>Данные</th></tr></thead><tbody>
@forelse($activity as $x)<tr>
<td>{{$x->created_at?->format('d.m.Y H:i:s')}}</td><td>{{$x->user?->full_name}}</td><td><strong>{{$x->label?:$x->action}}</strong><div class="small text-secondary">{{$x->action}}</div></td>
<td>{{$x->program?->title??'—'}}</td><td>{{$x->section?->title??'—'}}</td><td>{{$x->item?->title??'—'}}</td><td>{{$x->ip??'—'}}</td><td><small>{{!empty($x->meta)?json_encode($x->meta,JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES):'—'}}</small></td>
</tr>@empty<tr><td colspan="8">Действий пока нет.</td></tr>@endforelse
</tbody></table></div>{{$activity->links()}}</div>

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