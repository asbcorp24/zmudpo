@extends('layouts.app')
@section('title','Дневник практики')
@section('content')
<div class="page-heading"><div><a class="back-link" href="{{route('learning.show',$item->section)}}">← К разделу</a><h1>{{$item->title}}</h1><p>Дневник практики.</p></div><button class="btn btn-outline-primary" onclick="window.print()">Печать</button></div>

<div class="panel mb-4"><h2>Добавить выполненную работу</h2>
<form method="post" action="{{route('learning.practice-diary.store',$item)}}">@csrf
<div class="grid">
<div><label>Дата</label><input class="form-control" type="date" name="work_date" value="{{now()->toDateString()}}" required></div>
<div><label>Куратор / руководитель</label><input class="form-control" name="supervisor"></div>
</div>
<label class="mt-3">Выполненная работа</label><textarea class="form-control" name="work_done" rows="5" required></textarea>
<button class="btn btn-primary mt-3">Добавить</button>
</form></div>

<div class="panel"><div class="table-responsive"><table class="table table-bordered align-middle">
<thead><tr><th style="width:140px">Дата</th><th>Выполненная работа</th><th style="width:220px">Куратор</th><th class="no-print"></th></tr></thead>
<tbody>@forelse($entries as $entry)<tr><form method="post" action="{{route('learning.practice-diary.update',$entry)}}">@csrf @method('PUT')
<td><input class="form-control" type="date" name="work_date" value="{{$entry->work_date?->format('Y-m-d')}}" required></td>
<td><textarea class="form-control" name="work_done" rows="3" required>{{$entry->work_done}}</textarea></td>
<td><input class="form-control" name="supervisor" value="{{$entry->supervisor}}"></td>
<td class="no-print"><button class="btn btn-sm btn-outline-primary">Сохранить</button></td>
</form></tr>@empty<tr><td colspan="4" class="text-secondary">Записей пока нет.</td></tr>@endforelse</tbody>
</table></div></div>
<style>@media print{.sidebar,.topbar,.no-print,.page-heading .btn{display:none!important}.main{margin:0!important}.content{padding:0!important}.form-control{border:0;padding:0;resize:none}.panel{box-shadow:none;border:0}}</style>
@endsection