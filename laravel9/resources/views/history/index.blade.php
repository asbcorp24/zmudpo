@extends('layouts.app')
@section('title','История обучения')
@section('content')
<div class="page-heading"><div><h1>История обучения</h1><p>Завершённые программы и архивные результаты.</p></div></div>
<div class="panel">
@forelse($records as $record)
<div class="list-row"><div><span class="course-type">{{ $record->type }}</span><strong>{{ $record->title ?: $record->program?->title ?: 'Запись' }}</strong><small>@if($record->started_at){{ $record->started_at->format('d.m.Y') }}@endif @if($record->ended_at) — {{ $record->ended_at->format('d.m.Y') }}@endif @if($record->score!==null) · результат {{ $record->score }}@endif</small></div>@if($record->program_id)<span class="status completed">архив</span>@endif</div>
@empty<p class="empty">История обучения пока пуста.</p>@endforelse
</div>
{{ $records->links() }}
@endsection
