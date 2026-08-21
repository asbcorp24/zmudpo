@extends('layouts.app')
@section('title','Расписание')
@section('content')
<div class="page-heading"><div><h1>Расписание и преподаватели</h1><p>Доступные занятия по вашим активным программам.</p></div></div>
@forelse($assignments as $assignment)
<div class="panel">
 <div class="panel-head"><div><h2>{{ $assignment->program?->title }}</h2><strong>{{ $assignment->instructor?->full_name }}</strong></div><span>{{ $assignment->subject }}</span></div>
 @if($assignment->comment)<p>{{ $assignment->comment }}</p>@endif
 @forelse($assignment->slots->sortBy('date') as $slot)
 @php($isBooked=in_array($slot->id,$booked,true)) @php($used=(int)($counts[$slot->id]??0))
 <div class="list-row"><div><strong>{{ optional($slot->date)->format('d.m.Y') }} {{ $slot->time }}</strong><small>Занятие №{{ $slot->lesson_number ?: '—' }} · мест: {{ $used }}@if($slot->capacity)/{{ $slot->capacity }}@endif @if($slot->comment) · {{ $slot->comment }}@endif</small></div>
 @if($isBooked)<form method="post" action="{{ route('student.schedule.cancel',$slot) }}">@csrf @method('DELETE')<button class="btn gray">Отменить запись</button></form>
 @elseif(!$slot->capacity || $used < $slot->capacity)<form method="post" action="{{ route('student.schedule.book',$slot) }}">@csrf<button class="btn">Записаться</button></form>
 @else<span class="status blocked">Мест нет</span>@endif</div>
 @empty<p class="empty">Расписание пока не опубликовано.</p>@endforelse
</div>
@empty<div class="panel empty">Для ваших программ преподаватели и расписание пока не назначены.</div>@endforelse
@endsection
