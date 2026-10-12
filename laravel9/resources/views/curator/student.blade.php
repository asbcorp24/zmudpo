@extends('layouts.app')
@section('title',$enrollment->user?->full_name??'Слушатель')
@section('content')
@include('curator._legacy_nav')
<div class="page-heading"><div><a href="{{route('curator.students')}}">← Слушатели</a><h1>{{$enrollment->user?->full_name}}</h1><p>{{$enrollment->program?->title}} · группа {{$enrollment->group?->name??'—'}}</p></div><div class="text-end"><span class="badge-soft">{{number_format((float)$enrollment->progress_percent,0)}}%</span><div class="small mt-2">Согласие ПД: {{$enrollment->user?->personal_data_consent_at?->format('d.m.Y H:i')??'не подтверждено'}}</div></div></div>

<div class="panel mb-3"><h2>Разделы</h2>@foreach($sections as $s) @php($p=$progress->get($s->id)) <div class="list-row"><div><strong>{{$s->title}}</strong><small>{{$p?->completed_at?'завершён '.$p->completed_at->format('d.m.Y H:i'):'в процессе'}} · материалов {{$s->contentItems->count()}}</small></div><span>{{$p?->progress_percent??0}}%</span></div>@endforeach</div>

<div class="panel mb-3"><h2>Хронология действий NMO</h2><div class="table-responsive"><table class="table table-hover"><thead><tr><th>Дата</th><th>Действие</th><th>Раздел</th><th>Материал</th><th>IP</th><th>Данные</th></tr></thead><tbody>
@forelse($activity as $x)<tr><td>{{$x->created_at?->format('d.m.Y H:i:s')}}</td><td><strong>{{$x->label?:$x->action}}</strong><div class="small text-secondary">{{$x->action}}</div></td><td>{{$x->section?->title??'—'}}</td><td>{{$x->item?->title??'—'}}</td><td>{{$x->ip??'—'}}</td><td><small>{{!empty($x->meta)?json_encode($x->meta,JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES):'—'}}</small></td></tr>@empty<tr><td colspan="6">Действий пока нет.</td></tr>@endforelse
</tbody></table></div></div>

<div class="panel mb-3"><h2>Действия по NMO</h2><div class="table-responsive"><table class="table table-hover"><thead><tr><th>Дата</th><th>Раздел</th><th>Материал</th><th>Тип</th><th>Статус</th><th>Оценка</th></tr></thead><tbody>
@forelse($responses as $x)<tr><td>{{$x->submitted_at?->format('d.m.Y H:i')}}</td><td>{{$x->item?->section?->title}}</td><td>{{$x->item?->title}}</td><td>tip {{$x->item?->legacy_type}}</td><td>{{$x->status}}</td><td>{{$x->score??'—'}}</td></tr>@empty<tr><td colspan="6">Ответов и файлов нет.</td></tr>@endforelse
</tbody></table></div></div>

<div class="panel mb-3"><h2>Тесты</h2>@forelse($attempts as $a)<div class="list-row"><div><strong>{{$a->assignment?->title}}</strong><small>{{$a->finished_at?->format('d.m.Y H:i')}}</small></div><span>{{$a->percent}}% · {{$a->passed?'пройден':'не пройден'}}</span></div>@empty<p class="empty">Попыток нет.</p>@endforelse</div>

<div class="panel mb-3"><h2>Билеты</h2>@forelse($tickets as $x)<div class="list-row"><div><strong>{{$x->item?->title}}</strong><small>{{$x->item?->section?->title}} · {{$x->created_at?->format('d.m.Y H:i')}}</small></div><span class="badge-soft">Билет {{$x->ticket_number}}</span></div>@empty<p class="empty">Билеты не выдавались.</p>@endforelse</div>

<div class="panel mb-3"><h2>Дневник практики</h2><div class="table-responsive"><table class="table"><thead><tr><th>Дата</th><th>Раздел</th><th>Работа</th><th>Руководитель</th></tr></thead><tbody>@forelse($diary as $x)<tr><td>{{$x->work_date?->format('d.m.Y')}}</td><td>{{$x->item?->section?->title}}</td><td>{{$x->work_done}}</td><td>{{$x->supervisor??'—'}}</td></tr>@empty<tr><td colspan="4">Записей нет.</td></tr>@endforelse</tbody></table></div></div>

<div class="panel mb-3"><h2>Отметки присутствия</h2>@forelse($visits as $x)<div class="list-row"><div>{{$x->section?->title}}</div><span>{{$x->visited_at?->format('d.m.Y H:i:s')}}</span></div>@empty<p class="empty">Отметок нет.</p>@endforelse</div>

<div class="panel mb-3"><h2>Прочитанные объявления</h2>@forelse($acks as $x)<div class="list-row"><div>{{$x->announcement?->title}}</div><span>{{$x->acknowledged_at?->format('d.m.Y H:i')}}</span></div>@empty<p class="empty">Нет подтверждений.</p>@endforelse</div>

<div class="panel mb-3"><h2>Практические работы</h2>@forelse($submissions as $s)<div class="list-row"><div><strong>{{$s->practiceAssignment?->title}}</strong><small>{{$s->submitted_at?->format('d.m.Y H:i')}}</small></div><span>{{$s->status}}</span></div>@empty<p class="empty">Работ нет.</p>@endforelse</div>

<div class="panel mb-3"><h2>История входов</h2>@forelse($logins as $l)<div class="list-row"><span>{{$l->logged_in_at}}</span><small>{{$l->ip}} · {{$l->user_agent}}</small></div>@empty<p class="empty">Входов нет.</p>@endforelse</div>

<div class="panel"><h2>Legacy NMO</h2>@forelse($nmo as $p)<div class="list-row"><div>Legacy раздел #{{$p->legacy_section_id}}</div><span>{{$p->completed?'пройден':'не завершён'}} · {{$p->legacy_date?->format('d.m.Y H:i')}}</span></div>@empty<p class="empty">Нет legacy-состояний.</p>@endforelse</div>
@endsection