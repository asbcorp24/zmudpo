@extends('layouts.app')
@section('title','Проверка работ')
@section('content')
@if(auth()->user()->role!=='admin') @include('curator._legacy_nav') @endif

<div class="panel mb-4">
    <div class="panel-head"><h2>Ответы по учебным материалам</h2><span class="badge-soft">{{$contentResponses->count()}} на проверке</span></div>
    @forelse($contentResponses as $x)
        <form method="post" action="{{ auth()->user()->role==='admin' ? route('admin.review.content',$x) : route('curator.review.content',$x) }}" class="border-top py-3">
            @csrf @method('PATCH')
            <div class="d-flex justify-content-between gap-3 flex-wrap mb-2">
                <div><strong>{{$x->user?->full_name}}</strong><div class="text-secondary small">{{$x->item?->section?->program?->title}} · {{$x->item?->section?->title}} · {{$x->item?->title}}</div></div>
                <span class="status active">{{optional($x->submitted_at)->format('d.m.Y H:i')}}</span>
            </div>
            @if($x->text)<div class="p-3 bg-light rounded-3 mb-2" style="white-space:pre-wrap">{{$x->text}}</div>@endif
            <div class="d-flex gap-2 flex-wrap mb-3">
                @if($x->file_path)<a class="btn btn-sm btn-outline-secondary" data-no-ajax href="{{route('learning.response.download',$x)}}">Скачать файл</a>@endif
                @foreach((array)$x->files as $i=>$path)<a class="btn btn-sm btn-outline-secondary" data-no-ajax href="{{route('learning.response.download',[$x,$i])}}">Файл {{$i+1}}</a>@endforeach
                @if(data_get($x->meta,'random_value')!==null)<span class="badge-soft">Билет / число: {{data_get($x->meta,'random_value')}}</span>@endif
            </div>
            <div class="grid">
                <div><label>Решение</label><select class="form-select" name="status"><option value="accepted">Принять</option><option value="revision">На доработку</option><option value="rejected">Отклонить</option></select></div>
                <div><label>Оценка</label><input class="form-control" type="number" step="0.01" name="score"></div>
                <div><label>Комментарий</label><textarea class="form-control" name="review_comment"></textarea></div>
            </div>
            <button class="btn btn-primary mt-3">Сохранить проверку</button>
        </form>
    @empty
        <p class="empty mb-0">Нет ответов по учебным материалам на проверку.</p>
    @endforelse
</div>

<div class="panel mb-4">
    <div class="panel-head"><h2>Практические работы</h2><span class="badge-soft">{{$submissions->count()}}</span></div>
    @forelse($submissions as $x)
        <form method="post" action="{{ auth()->user()->role==='admin'?route('admin.review.submission',$x):route('curator.review.submission',$x) }}" class="border-top py-3">
            @csrf @method('PATCH')
            <b>{{ $x->user->full_name }}</b> — {{ optional($x->practiceAssignment)->title }}
            @if($x->file_path)<a class="btn btn-sm btn-outline-secondary ms-2" data-no-ajax href="{{ route('practice.download',$x) }}">Скачать файл</a>@endif
            <div class="grid mt-2"><div><label>Решение</label><select class="form-select" name="status"><option value="accepted">Принять</option><option value="revision">На доработку</option><option value="rejected">Отклонить</option></select></div><div><label>Оценка</label><input class="form-control" type="number" step="0.01" name="score"></div><div><label>Комментарий</label><textarea class="form-control" name="review_comment"></textarea></div></div>
            <button class="btn btn-primary mt-3">Сохранить</button>
        </form>
    @empty<p class="empty mb-0">Нет практических работ на проверку.</p>@endforelse
</div>

<div class="panel">
    <div class="panel-head"><h2>Итоговые работы</h2><span class="badge-soft">{{$finalWorks->count()}}</span></div>
    @forelse($finalWorks as $x)
        <form method="post" action="{{ auth()->user()->role==='admin'?route('admin.review.final',$x):route('curator.review.final',$x) }}" class="border-top py-3">
            @csrf @method('PATCH')
            <b>{{ $x->user->full_name }}</b> — {{ optional(optional($x->enrollment)->program)->title }}
            @if($x->file_path)<a class="btn btn-sm btn-outline-secondary ms-2" data-no-ajax href="{{ route('final-works.download',$x) }}">Скачать файл</a>@endif
            <div class="grid mt-2"><div><label>Решение</label><select class="form-select" name="status"><option value="accepted">Принять</option><option value="revision">На доработку</option><option value="rejected">Отклонить</option></select></div><div><label>Оценка</label><input class="form-control" type="number" step="0.01" name="score"></div><div><label>Антиплагиат, %</label><input class="form-control" type="number" name="antiplagiarism_percent" min="0" max="100"></div><div><label>Комментарий</label><textarea class="form-control" name="review_comment"></textarea></div></div>
            <button class="btn btn-primary mt-3">Сохранить</button>
        </form>
    @empty<p class="empty mb-0">Нет итоговых работ на проверку.</p>@endforelse
</div>
@endsection
