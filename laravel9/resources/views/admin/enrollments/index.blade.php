@extends('layouts.app')
@section('title','Назначения и доступ')
@section('content')
<div class="page-heading"><div><h1>Назначения и доступ</h1><p>Фильтры, группы, кураторы и массовое изменение слушателей.</p></div></div>

<div class="panel mb-4"><form method="get" class="grid">
<div><label>Статус</label><select class="form-select" name="status"><option value="">Все</option>@foreach(['active'=>'Активные','blocked'=>'Заблокированные','completed'=>'Завершённые','archived'=>'Архив'] as $k=>$v)<option value="{{$k}}" @selected(request('status')===$k)>{{$v}}</option>@endforeach</select></div>
<div><label>Программа</label><select class="form-select" name="program_id"><option value="">Все</option>@foreach($programs as $p)<option value="{{$p->id}}" @selected((int)request('program_id')===$p->id)>{{$p->title}}</option>@endforeach</select></div>
<div><label>Группа</label><select class="form-select" name="group_id"><option value="">Все</option>@foreach($groups as $g)<option value="{{$g->id}}" @selected((int)request('group_id')===$g->id)>{{$g->name}}</option>@endforeach</select></div>
<div class="d-flex align-items-end gap-2"><button class="btn btn-primary">Фильтр</button><a class="btn btn-outline-secondary" href="{{route('admin.enrollments.index')}}">Сбросить</a></div>
</form></div>

<form method="post" action="{{route('admin.enrollments.bulk')}}">@csrf
<div class="panel mb-4"><div class="panel-head"><h2>Массовое изменение выбранных</h2></div><div class="grid">
<div><label>Группа</label><select class="form-select" name="group_id"><option value="">Не менять</option>@foreach($groups as $g)<option value="{{$g->id}}">{{$g->name}}</option>@endforeach</select></div>
<div><label>Куратор</label><select class="form-select" name="curator_id"><option value="">Не менять</option>@foreach($curators as $u)<option value="{{$u->id}}">{{$u->full_name}}</option>@endforeach</select></div>
<div><label>Статус</label><select class="form-select" name="status"><option value="">Не менять</option>@foreach(['active','blocked','completed','archived'] as $s)<option value="{{$s}}">{{$s}}</option>@endforeach</select></div>
<div><label>Начало</label><input class="form-control" type="date" name="started_at"></div>
<div><label>Окончание</label><input class="form-control" type="date" name="ends_at"></div>
</div><button class="btn btn-primary mt-3">Применить к выбранным</button></div>

<div class="panel"><div class="table-responsive"><table class="table table-hover align-middle">
<thead><tr><th><input type="checkbox" onclick="document.querySelectorAll('.enr-check').forEach(x=>x.checked=this.checked)"></th><th>Слушатель</th><th>Программа</th><th>Группа</th><th>Куратор</th><th>Статус</th><th>Прогресс</th><th></th></tr></thead>
<tbody>@foreach($items as $e)<tr><td><input class="enr-check" type="checkbox" name="ids[]" value="{{$e->id}}"></td><td>{{$e->user?->full_name}}</td><td>{{$e->program?->title}}</td><td>{{$e->group?->name??'—'}}</td><td>{{$e->curator?->full_name??'—'}}</td><td>{{$e->status}}</td><td>{{number_format((float)$e->progress_percent,0)}}%</td><td><button class="btn btn-sm btn-outline-primary" type="button" data-bs-toggle="collapse" data-bs-target="#enr{{$e->id}}">Изменить</button></td></tr>
<tr class="collapse" id="enr{{$e->id}}"><td colspan="8"><form method="post" action="{{route('admin.enrollments.update',$e)}}">@csrf @method('PATCH')<div class="grid">
<div><label>Статус</label><select name="status">@foreach(['active','blocked','completed','archived'] as $s)<option @selected($e->status===$s)>{{$s}}</option>@endforeach</select></div>
<div><label>Группа</label><select name="group_id"><option value="">—</option>@foreach($groups as $g)<option value="{{$g->id}}" @selected($e->group_id===$g->id)>{{$g->name}}</option>@endforeach</select></div>
<div><label>Куратор</label><select name="curator_id"><option value="">—</option>@foreach($curators as $u)<option value="{{$u->id}}" @selected($e->curator_id===$u->id)>{{$u->full_name}}</option>@endforeach</select></div>
<div><label>Начало</label><input type="date" name="started_at" value="{{$e->started_at?->format('Y-m-d')}}"></div><div><label>Окончание</label><input type="date" name="ends_at" value="{{$e->ends_at?->format('Y-m-d')}}"></div></div><label>Комментарий</label><textarea name="admin_comment">{{$e->admin_comment}}</textarea><button class="btn mt-2">Сохранить</button></form></td></tr>
@endforeach</tbody></table></div>{{$items->links()}}</div>
</form>
@endsection