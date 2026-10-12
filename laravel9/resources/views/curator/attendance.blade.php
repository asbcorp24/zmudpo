@extends('layouts.app')
@section('title','Журнал посещаемости')
@section('content')
@include('curator._legacy_nav')
<div class="page-heading"><div><h1>Журнал посещаемости</h1><p>Ручные отметки куратора, входы на сайт и legacy-история.</p></div></div>

<div class="panel mb-4"><form method="get" class="grid">
<div><label>Дата</label><input class="form-control" type="date" name="date" value="{{$date}}"></div>
<div><label>Программа</label><select class="form-select" name="program_id"><option value="">Все</option>@foreach($programs as $p)<option value="{{$p->id}}" @selected((int)request('program_id')===$p->id)>{{$p->title}}</option>@endforeach</select></div>
<div><label>Группа</label><select class="form-select" name="group_id"><option value="">Все</option>@foreach($groups as $g)<option value="{{$g->id}}" @selected((int)request('group_id')===$g->id)>{{$g->name}}</option>@endforeach</select></div>
<div class="d-flex align-items-end gap-2"><button class="btn btn-primary">Показать</button><a class="btn btn-outline-secondary" href="{{route('curator.attendance')}}">Сегодня</a></div>
</form></div>

<div class="panel"><div class="table-responsive"><table class="table table-hover align-middle">
<thead><tr><th>Группа</th><th>ФИО</th><th>Программа</th><th>Отметка</th><th>Минуты</th><th>Комментарий</th><th>Входы за день</th><th></th></tr></thead>
<tbody>@foreach($enrollments as $e)@php($m=$records[$e->id]??null)<tr>
<td>{{$e->group?->name??'—'}}</td><td><a href="{{route('curator.student',$e)}}">{{$e->user?->full_name}}</a></td><td>{{$e->program?->title}}</td>
<td colspan="5"><form method="post" action="{{route('curator.attendance.mark',$e)}}" class="row g-2 align-items-center">@csrf
<input type="hidden" name="attendance_date" value="{{$date}}">
<div class="col-md-2"><select class="form-select form-select-sm" name="status"><option value="present" @selected($m?->status==='present')>Я — присутствовал</option><option value="absent" @selected($m?->status==='absent')>Н — отсутствовал</option><option value="excused" @selected($m?->status==='excused')>У — уважительная</option><option value="late" @selected($m?->status==='late')>О — опоздание</option></select></div>
<div class="col-md-1"><input class="form-control form-control-sm" type="number" min="0" max="1440" name="minutes" value="{{$m?->minutes}}" placeholder="мин"></div>
<div class="col-md-3"><input class="form-control form-control-sm" name="comment" value="{{$m?->comment}}" placeholder="Комментарий"></div>
<div class="col-md-4">@forelse(($logins[$e->user_id]??collect()) as $l)<span class="badge-soft">{{CarbonCarbon::parse($l->logged_in_at)->format('H:i')}}</span> @empty<span class="text-secondary">нет входов</span>@endforelse</div>
<div class="col-md-2"><button class="btn btn-sm btn-primary w-100">Сохранить</button></div>
</form></td>
</tr>@endforeach</tbody></table></div></div>
@endsection