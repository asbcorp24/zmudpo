@extends('layouts.app')
@section('title',$item->title)
@section('page-title','Таблица')
@section('content')
<div class="page-heading">
  <div><a class="back-link" href="{{route('learning.show',$item->section)}}">← Назад к разделу</a><h1>{{$item->title}}</h1>@if($item->body)<p>{{$item->body}}</p>@endif</div>
  <a class="btn btn-outline-primary" data-no-ajax href="{{route('learning.download',$item)}}">Скачать CSV</a>
</div>
<div class="panel">
  @if(count($rows))
  <div class="table-responsive"><table class="table table-hover align-middle">
    @foreach($rows as $ri=>$row)
      <tr>@foreach($row as $cell) @if($ri===0)<th>{{trim($cell)}}</th>@else<td>{{trim($cell)}}</td>@endif @endforeach</tr>
    @endforeach
  </table></div>
  @else
  <div class="empty">Таблица пуста.</div>
  @endif
</div>
@endsection
