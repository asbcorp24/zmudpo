@extends('layouts.app')
@section('title','Документы')
@section('page-title','Документы и удостоверения')
@section('content')
<div class="page-heading"><div><h1>Документы</h1><p>Учебные документы, сертификаты, удостоверения и архив.</p></div></div>
<div class="panel">
@forelse($documents as $d)
<div class="list-row"><div><span class="course-type">{{ $d->type }}</span><strong>{{ $d->title }}</strong><small>{{ $d->number ? '№ '.$d->number : '' }} {{ $d->issued_at?->format('d.m.Y') }} @if($d->program) · {{ $d->program->title }} @endif</small></div>@if($d->file_path)<a class="btn btn-sm btn-outline-primary" href="{{ route('documents.download',$d) }}">Скачать</a>@endif</div>
@empty<p class="empty">Документов пока нет.</p>@endforelse
</div>
@endsection
