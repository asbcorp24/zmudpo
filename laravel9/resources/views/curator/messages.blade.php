@extends('layouts.app')
@section('title','Сообщения слушателей')
@section('content')
<div class="page-heading"><div><h1>Сообщения слушателей</h1><p>Диалоги по назначенным вам программам.</p></div></div>
@forelse($enrollments as $enrollment)
<div class="panel"><div class="panel-head"><div><h2>{{ $enrollment->user->full_name }}</h2><span>{{ $enrollment->program->title }}</span></div></div>
<div style="max-height:360px;overflow:auto;margin-bottom:14px">
@forelse($messages->get($enrollment->id,collect()) as $m)<div style="margin:8px 0;padding:10px 12px;border-radius:10px;background:{{ $m->from_curator?'#eef4ff':'#f4f7fb' }}"><strong>{{ $m->from_curator?'Вы':$enrollment->user->full_name }}</strong><div>{{ $m->message }}</div><small>{{ $m->created_at->format('d.m.Y H:i') }}</small></div>@empty<p class="empty">Сообщений пока нет.</p>@endforelse
</div>
<form method="post" action="{{ route('curator.messages.send',$enrollment) }}">@csrf<textarea name="message" rows="3" required placeholder="Ответ слушателю"></textarea><button class="btn" style="margin-top:10px">Отправить ответ</button></form>
</div>
@empty<div class="panel empty">Нет назначенных слушателей.</div>@endforelse
@endsection
