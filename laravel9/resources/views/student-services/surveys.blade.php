@extends('layouts.app')
@section('title','Опросы')
@section('content')
<div class="page-heading"><div><h1>Опросы и анкеты</h1><p>Заполните доступные анкеты. Ответы можно обновить повторно.</p></div></div>
@forelse($surveys as $survey)
<div class="panel"><div class="panel-head"><h2>{{ $survey->title }}</h2></div>
<form method="post" action="{{ route('student.surveys.submit',$survey) }}">@csrf
@foreach($survey->fields as $field)
 @php($response=$responses->get($field->id)?->value)
 <label>{{ $field->title }}</label>
 @if($field->type==='textarea')<textarea name="field_{{ $field->id }}" rows="4">{{ old('field_'.$field->id,$response) }}</textarea>
 @elseif($field->type==='select')<select name="field_{{ $field->id }}"><option value="">—</option>@foreach(($field->options??[]) as $option)<option value="{{ $option }}" @selected($response==$option)>{{ $option }}</option>@endforeach</select>
 @elseif(in_array($field->type,['radio','boolean']))
   @foreach(($field->options?:['Да','Нет']) as $option)<label style="font-weight:400"><input style="width:auto" type="radio" name="field_{{ $field->id }}" value="{{ $option }}" @checked($response==$option)> {{ $option }}</label>@endforeach
 @else<input name="field_{{ $field->id }}" value="{{ old('field_'.$field->id,$response) }}">@endif
@endforeach
<button class="btn" style="margin-top:14px">Сохранить ответы</button></form></div>
@empty<div class="panel empty">Активных опросов сейчас нет.</div>@endforelse
@endsection
