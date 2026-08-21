@extends('layouts.app')
@section('title','Результат тестирования')
@section('content')
<div class="page-heading"><div><a class="back-link" href="{{route('quizzes.index')}}">← Все тесты</a><h1>{{$attempt->assignment?->title ?? $attempt->quiz?->title ?? 'Тест'}}</h1><p>{{$attempt->assignment?->program?->title}}</p></div><div class="badge-soft">{{number_format((float)$attempt->percent,0)}}% · {{$attempt->passed?'пройден':'не пройден'}}</div></div>
<div class="panel"><p><strong>Баллы:</strong> {{$attempt->score}}</p><p><strong>Дата:</strong> {{$attempt->finished_at?->format('d.m.Y H:i')}}</p></div>
@if($detail && $detail->questions)
<div class="panel"><div class="panel-head"><h2>Разбор ответов</h2></div>
@foreach($detail->questions as $i=>$q)
<div class="card" style="box-shadow:none;border:1px solid #e6e9ef">
 <h3>{{($i+1).'. '.($q['question']??'Вопрос')}}</h3>
 <p><strong>Ваш ответ:</strong> {{implode('; ',$q['selected_answers']??[]) ?: 'Ответ не выбран'}}</p>
 <p><strong>Правильный ответ:</strong> {{implode('; ',$q['correct_answers']??[]) ?: '—'}}</p>
 <p class="{{$q['correct']??false?'ok':'err'}}">{{$q['correct']??false?'Верно':'Неверно'}}</p>
</div>
@endforeach
</div>
@else<div class="panel"><p>Для этой попытки сохранён общий результат без детализации по вопросам. Это возможно для импортированных legacy-результатов.</p></div>@endif
@endsection
