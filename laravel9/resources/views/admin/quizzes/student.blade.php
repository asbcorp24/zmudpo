@extends('layouts.app')
@section('title','Результаты слушателя')
@section('content')
<div class="card"><h2>{{$row['user']->full_name}}</h2><p>{{$program->title}}</p><p>Режим: {{$mode==='best'?'лучшая попытка':'последняя попытка'}}.</p><div class="grid"><div>Средний результат: <b>{{$row['average']!==null?$row['average'].'%':'—'}}</b></div><div>Оценка: <b>{{$row['grade']??'—'}}</b></div><div>Пройдено: <b>{{$row['passed']}}</b></div><div>Не пройдено: <b>{{$row['failed']}}</b></div><div>Нет результата: <b>{{$row['missing']}}</b></div></div></div>
<div class="card" style="overflow:auto"><table><tr><th>Тест</th><th>Результат</th><th>Оценка</th><th>Попыток</th><th>Статус</th><th>Дата</th></tr>@foreach($assignments as $a)@php($cell=$row['cells'][$a->id])<tr><td>{{$a->title}}</td><td>{{$cell['attempt']?$cell['percent'].'%':'—'}}</td><td>{{$cell['grade']??'—'}}</td><td>{{$cell['attempts']}}</td><td>@if($cell['attempt']){{$cell['attempt']->passed?'Пройден':'Не пройден'}}@else Нет результата @endif</td><td>{{$cell['attempt']?->finished_at?->format('d.m.Y H:i')??'—'}}</td></tr>@endforeach</table></div>
@endsection
