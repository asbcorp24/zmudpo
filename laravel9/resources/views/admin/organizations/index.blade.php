@extends('layouts.app')
@section('title','Организации')
@section('content')
<div class="page-heading"><div><h1>Организации</h1><p>Юридические лица, их сотрудники и программы обучения.</p></div><a class="btn btn-primary" href="{{route('admin.users.create')}}">Добавить организацию</a></div>
<div class="panel mb-4"><form method="get" class="d-flex gap-2"><input class="form-control" name="q" value="{{request('q')}}" placeholder="Название, ИНН, e-mail, телефон"><button class="btn btn-primary">Найти</button></form></div>
<div class="panel"><div class="table-responsive"><table class="table table-hover align-middle"><thead><tr><th>Организация</th><th>ИНН</th><th>Контакт</th><th>Сотрудников</th><th>Активна</th><th></th></tr></thead><tbody>
@forelse($items as $x)<tr><td><strong>{{$x->organizationProfile?->short_name ?: $x->full_name}}</strong><div class="small text-secondary">{{$x->full_name}}</div></td><td>{{$x->organizationProfile?->inn??'—'}}</td><td>{{$x->organizationProfile?->contact_person??'—'}}<div class="small text-secondary">{{$x->email}} {{$x->phone}}</div></td><td>{{$x->employees->count()}}</td><td>{{$x->is_active?'Да':'Нет'}}</td><td><a class="btn btn-sm btn-outline-primary" href="{{route('admin.organizations.edit',$x)}}">Карточка</a></td></tr>@empty<tr><td colspan="6" class="text-secondary">Организаций пока нет.</td></tr>@endforelse
</tbody></table></div>{{$items->links()}}</div>
@endsection
