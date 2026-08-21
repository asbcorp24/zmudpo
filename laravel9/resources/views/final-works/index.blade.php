@extends('layouts.app')
@section('title','Итоговые работы')
@section('content')
<div class="page-heading"><div><h1>Итоговые работы</h1><p>Выберите тему, загрузите работу и отслеживайте результат проверки.</p></div></div>
@foreach($enrollments as $enrollment)
 @php($programDefinitions=$definitions->filter(fn($d)=>$d->programs()->where('programs.id',$enrollment->program_id)->exists()))
 @if($programDefinitions->isNotEmpty())
 <div class="panel">
  <div class="panel-head"><h2>{{ $enrollment->program->title }}</h2><span class="status {{ $enrollment->status }}">{{ $enrollment->status }}</span></div>
  @foreach($programDefinitions as $definition)
   @php($work=$works->get($enrollment->id.':'.$definition->id))
   <div class="card" style="box-shadow:none;border:1px solid #e6e9ef">
    <h3>{{ $definition->title }}</h3>
    @if($work)
     <p><strong>Статус:</strong> {{ $work->status }} @if($work->submitted_at) · отправлено {{ $work->submitted_at->format('d.m.Y H:i') }} @endif</p>
     @if($work->review_comment)<div class="review-note"><strong>Комментарий проверяющего:</strong> {{ $work->review_comment }}</div>@endif
     @if(in_array($work->status,['accepted','passed']))<p class="ok">Работа принята.</p>@endif
    @endif
    @if($enrollment->status==='active' && !($work && in_array($work->status,['accepted','passed'])))
    <form method="post" enctype="multipart/form-data" action="{{ route('final-works.submit',$enrollment) }}">@csrf
     <input type="hidden" name="definition_id" value="{{ $definition->id }}">
     @if($definition->themes->isNotEmpty())<label>Тема</label><select name="theme_id"><option value="">Своя тема</option>@foreach($definition->themes as $theme)<option value="{{ $theme->id }}">{{ $theme->title }}</option>@endforeach</select>@endif
     <label>Название работы</label><input name="title" value="{{ old('title',$work?->title) }}" placeholder="Если не указано, используется выбранная тема">
     <label>Файл</label><input type="file" name="file" accept=".pdf,.doc,.docx,.odt,.rtf" required>
     <label>Комментарий</label><textarea name="comment" rows="3">{{ old('comment',$work?->comment) }}</textarea>
     <button class="btn" style="margin-top:12px">{{ $work?'Отправить новую версию':'Отправить на проверку' }}</button>
    </form>
    @endif
   </div>
  @endforeach
 </div>
 @endif
@endforeach
@if($definitions->isEmpty())<div class="panel empty">Для ваших программ итоговые работы не назначены.</div>@endif
@endsection
