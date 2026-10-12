@extends('layouts.app')
@section('title','Статистика')
@section('content')
@include('curator._legacy_nav')
<div class="page-heading"><div><h1>{{request()->routeIs('curator.tables')?'Таблицы':'Статистика'}}</h1><p>Сводка обучения, тестов, практики и интерактивных заданий.</p></div><button class="btn btn-outline-primary" onclick="window.print()">Печать</button></div>
<div class="panel"><div class="table-responsive"><table class="table table-hover align-middle">
<thead><tr><th>Группа</th><th>ФИО</th><th>Программа</th><th>Прогресс</th><th>Тесты</th><th>Средний %</th><th>Практика</th><th>Задания НМО</th><th>На проверке</th></tr></thead>
<tbody>@foreach($enrollments as $e)
@php($q=$quiz[$e->user_id]??null) @php($p=$practice[$e->user_id]??null) @php($c=$content[$e->user_id]??null)
<tr><td>{{$e->group?->name??'—'}}</td><td><a href="{{route('curator.student',$e)}}">{{$e->user?->full_name}}</a></td><td>{{$e->program?->title}}</td><td><strong>{{number_format((float)$e->progress_percent,0)}}%</strong></td><td>{{$q?->passed??0}} / {{$q?->attempts??0}}</td><td>{{number_format((float)($q?->avg_percent??0),1)}}%</td><td>{{$p?->accepted??0}} / {{$p?->total??0}}</td><td>{{$c?->accepted??0}} / {{$c?->total??0}}</td><td>@if(($c?->pending??0)>0)<span class="status blocked">{{$c->pending}}</span>@else — @endif</td></tr>
@endforeach</tbody></table></div></div>
@endsection
