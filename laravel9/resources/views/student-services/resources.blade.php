@extends('layouts.app')
@section('title','Библиотека')
@section('content')
<div class="page-heading"><div><h1>Дополнительные материалы</h1><p>Документы, ссылки и дополнительные ресурсы ваших программ.</p></div></div>
<div class="card-grid">
@forelse($resources as $resource)
<div class="course-card"><span class="course-type">{{ strtoupper($resource->type) }}</span><h3>{{ $resource->title }}</h3>@if($resource->comment)<p>{{ $resource->comment }}</p>@endif @if($resource->dated_at)<small>{{ $resource->dated_at->format('d.m.Y') }}</small>@endif<div style="margin-top:12px">@if($resource->file_path)<a class="btn" href="{{ route('student.resources.download',$resource) }}">Скачать</a>@endif @if($resource->external_url)<a class="btn gray" target="_blank" rel="noopener" href="{{ $resource->external_url }}">Открыть ресурс</a>@endif</div></div>
@empty<div class="panel empty">Дополнительных материалов пока нет.</div>@endforelse
</div>
@endsection
