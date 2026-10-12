@extends('layouts.app')
@section('title','Архивная запись')
@section('content')
<div class="page-heading"><div><a class="back-link" href="{{route('admin.legacy.archive')}}">← Архив</a><h1>{{$record->title ?: 'Архивная запись #'.$record->id}}</h1><p>{{$record->type}} · {{$record->created_at?->format('d.m.Y H:i')}}</p></div></div>
<div class="row g-4">
 <div class="col-lg-4"><div class="panel"><h2>Основное</h2>
  <div class="list-row"><span>Слушатель</span><strong>{{$record->user?->full_name??'—'}}</strong></div>
  <div class="list-row"><span>Программа</span><strong>{{$record->program?->title??'—'}}</strong></div>
  <div class="list-row"><span>Начало</span><strong>{{$record->started_at?->format('d.m.Y')??'—'}}</strong></div>
  <div class="list-row"><span>Окончание</span><strong>{{$record->ended_at?->format('d.m.Y')??'—'}}</strong></div>
  <div class="list-row"><span>Оценка</span><strong>{{$record->score??'—'}}</strong></div>
 </div></div>
 <div class="col-lg-8"><div class="panel"><h2>Снимок данных</h2><pre class="bg-light rounded-3 p-3 mb-0" style="white-space:pre-wrap">{{json_encode($record->data,JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES)}}</pre></div></div>
</div>
@endsection
