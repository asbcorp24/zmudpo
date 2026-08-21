@extends('layouts.app')
@section('title','Куратор')
@section('content')
<div class="page-heading"><div><h1>Переписка с куратором</h1><p>Вопросы по обучению и ответы по каждой программе.</p></div></div>
@forelse($enrollments as $enrollment)
<div class="panel">
 <div class="panel-head"><h2>{{ $enrollment->program->title }}</h2><span>{{ $enrollment->curator?->full_name ?: 'Куратор не назначен' }}</span></div>
 <div style="max-height:360px;overflow:auto;margin-bottom:14px">
 @forelse($messages->get($enrollment->id,collect()) as $m)
  <div style="margin:8px 0;padding:10px 12px;border-radius:10px;background:{{ $m->from_curator?'#eef4ff':'#f4f7fb' }}">
   <strong>{{ $m->from_curator ? ($m->curator?->full_name ?: 'Куратор') : 'Вы' }}</strong>
   <div>{{ $m->message }}</div><small>{{ $m->created_at->format('d.m.Y H:i') }}</small>
  </div>
 @empty<p class="empty">Сообщений пока нет.</p>@endforelse
 </div>
 @if($enrollment->curator_id)<form method="post" action="{{ route('student.messages.send',$enrollment) }}">@csrf<textarea name="message" rows="3" required placeholder="Ваш вопрос куратору"></textarea><button class="btn" style="margin-top:10px">Отправить</button></form>@endif
</div>
@empty<div class="panel empty">Нет программ для переписки.</div>@endforelse
@endsection
