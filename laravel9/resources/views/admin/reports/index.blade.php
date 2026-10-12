@extends('layouts.app')
@section('title','Печатные отчёты')
@section('content')
<div class="page-heading"><div><h1>Печатные отчёты</h1><p>Журнал посещаемости и ведомость успеваемости по программе и группе.</p></div></div>
<div class="panel"><form method="get" class="grid">
<div><label>Программа</label><select class="form-select" name="program_id"><option value="">Все</option>@foreach($programs as $p)<option value="{{$p->id}}">{{$p->title}}</option>@endforeach</select></div>
<div><label>Группа</label><select class="form-select" name="group_id"><option value="">Все</option>@foreach($groups as $g)<option value="{{$g->id}}">{{$g->name}}</option>@endforeach</select></div>
<div><label>С даты</label><input class="form-control" type="date" name="date_from"></div><div><label>По дату</label><input class="form-control" type="date" name="date_to"></div>
</form><div class="d-flex gap-2 mt-3"><button class="btn btn-primary" onclick="const f=this.closest('.panel').querySelector('form');f.action='{{route('admin.reports.attendance')}}';f.submit()">Журнал посещаемости</button><button class="btn btn-outline-primary" onclick="const f=this.closest('.panel').querySelector('form');f.action='{{route('admin.reports.achievement')}}';f.submit()">Ведомость успеваемости</button></div></div>
@endsection