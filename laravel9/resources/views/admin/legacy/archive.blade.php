@extends('layouts.app')
@section('title','Архив обучения')
@section('content')
<div class="page-heading">
  <div><h1>Архив обучения</h1><p>Завершённые программы, legacy-снимки и данные для восстановления документов.</p></div>
  <a class="btn btn-outline-primary" data-no-ajax href="{{route('admin.legacy.archive-export',request()->query())}}">Экспорт CSV</a>
</div>

<div class="panel mb-4">
  <form method="get" action="{{route('admin.legacy.archive')}}" class="grid">
    <div><label>Поиск</label><input class="form-control" name="q" value="{{request('q')}}" placeholder="ФИО, программа, название"></div>
    <div><label>Тип</label><select class="form-select" name="type"><option value="">Все</option>@foreach($types as $type)<option value="{{$type}}" @selected(request('type')===$type)>{{$type}}</option>@endforeach</select></div>
    <div><label>Программа</label><select class="form-select" name="program_id"><option value="">Все</option>@foreach($programs as $p)<option value="{{$p->id}}" @selected((int)request('program_id')===$p->id)>{{$p->title}}</option>@endforeach</select></div>
    <div class="d-flex align-items-end gap-2"><button class="btn btn-primary">Применить</button><a class="btn btn-outline-secondary" href="{{route('admin.legacy.archive')}}">Сбросить</a></div>
  </form>
</div>

<div class="panel mb-4">
  <div class="panel-head"><h2>Импорт старого XML</h2></div>
  <form method="post" enctype="multipart/form-data" action="{{route('admin.legacy.archive-import')}}">@csrf
    <div class="row g-3 align-items-end"><div class="col-lg-8"><label class="form-label">Legacy XML архива</label><input class="form-control" type="file" name="file" accept=".xml" required></div><div class="col-lg-4"><button class="btn btn-primary w-100">Импортировать снимок</button></div></div>
  </form>
</div>

<div class="panel">
  <div class="table-responsive"><table class="table table-hover align-middle">
    <thead><tr><th>Дата</th><th>Тип</th><th>Слушатель</th><th>Программа</th><th>Название</th><th>Период</th><th></th></tr></thead>
    <tbody>@forelse($items as $x)<tr>
      <td>{{$x->created_at?->format('d.m.Y H:i')}}</td><td><span class="badge-soft">{{$x->type}}</span></td>
      <td>{{$x->user?->full_name ?: ($x->type==='student_snapshot'?$x->title:'—')}}</td><td>{{$x->program?->title??'—'}}</td>
      <td>{{$x->title}}</td><td>{{$x->started_at?->format('d.m.Y')??'—'}} — {{$x->ended_at?->format('d.m.Y')??'—'}}</td>
      <td><a class="btn btn-sm btn-outline-primary" href="{{route('admin.legacy.archive-show',$x)}}">Открыть</a></td>
    </tr>@empty<tr><td colspan="7" class="text-secondary">Архив пуст.</td></tr>@endforelse</tbody>
  </table></div>
  {{$items->links()}}
</div>
@endsection
