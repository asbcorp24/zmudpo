@extends('layouts.app')
@section('title','Проверка IP')
@section('content')
@include('curator._legacy_nav')
<div class="page-heading"><div><h1>Проверка IP и входов</h1><p>Новые авторизации, общий IP нескольких слушателей и архив tm_login_dat.</p></div></div>
<div class="panel mb-4"><form method="get" class="grid"><div><label>IP</label><input class="form-control" name="ip" value="{{request('ip')}}"></div><div><label>С даты</label><input class="form-control" type="date" name="date_from" value="{{request('date_from')}}"></div><div><label>По дату</label><input class="form-control" type="date" name="date_to" value="{{request('date_to')}}"></div><div class="d-flex align-items-end gap-2"><button class="btn btn-primary">Фильтр</button><a class="btn btn-outline-secondary" href="{{route('curator.logins')}}">Сбросить</a></div></form></div>

@if($duplicates->isNotEmpty())<div class="panel mb-4"><div class="panel-head"><h2>Одинаковый IP у разных слушателей</h2></div><div class="table-responsive"><table class="table"><tr><th>IP</th><th>Слушателей</th><th>Входов</th></tr>@foreach($duplicates as $d)<tr><td><code>{{$d->ip}}</code></td><td>{{$d->users_count}}</td><td>{{$d->logins_count}}</td></tr>@endforeach</table></div></div>@endif

<div class="panel mb-4"><h2>Входы после перехода</h2><div class="table-responsive"><table class="table table-hover"><tr><th>Дата</th><th>Слушатель</th><th>IP</th><th>User-Agent</th></tr>@if($items) @foreach($items as $l) @php($e=$enrollments->firstWhere('user_id',$l->user_id)) <tr><td>{{$l->logged_in_at}}</td><td>{{$e?->user?->full_name}}</td><td><code>{{$l->ip}}</code></td><td><small>{{$l->user_agent}}</small></td></tr>@endforeach @endif</table></div>@if($items){{$items->links()}}@endif</div>
<div class="panel"><h2>Архив tm_login_dat</h2>@if($legacy)<div class="table-responsive"><table class="table"><tr><th>Дата</th><th>Слушатель</th><th>Доп. данные</th></tr>@foreach($legacy as $l)<tr><td>{{$l->occurred_at?->format('d.m.Y H:i:s')}}</td><td>{{$l->user?->full_name??('Legacy user #'.$l->legacy_user_id)}}</td><td>{{$l->body??'—'}}</td></tr>@endforeach</table></div>{{$legacy->links()}}@else<p class="empty">История ещё не импортирована.</p>@endif</div>
@endsection
