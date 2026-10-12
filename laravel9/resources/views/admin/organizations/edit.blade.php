@extends('layouts.app')
@section('title',$organization->full_name)
@section('content')
<div class="page-heading"><div><a class="back-link" href="{{route('admin.organizations.index')}}">← Организации</a><h1>{{$organization->organizationProfile?->short_name ?: $organization->full_name}}</h1><p>Юридическое лицо и сотрудники.</p></div><span class="badge-soft">{{$organization->employees->count()}} сотрудников</span></div>

<div class="panel mb-4"><h2>Реквизиты</h2><form method="post" action="{{route('admin.organizations.update',$organization)}}">@csrf @method('PUT')
<div class="grid">
<div><label>Полное наименование</label><input class="form-control" name="full_name" value="{{$organization->full_name}}" required></div>
<div><label>Краткое наименование</label><input class="form-control" name="short_name" value="{{$organization->organizationProfile?->short_name}}"></div>
<div><label>ИНН</label><input class="form-control" name="inn" value="{{$organization->organizationProfile?->inn}}"></div>
<div><label>КПП</label><input class="form-control" name="kpp" value="{{$organization->organizationProfile?->kpp}}"></div>
<div><label>ОГРН</label><input class="form-control" name="ogrn" value="{{$organization->organizationProfile?->ogrn}}"></div>
<div><label>E-mail</label><input class="form-control" type="email" name="email" value="{{$organization->email}}"></div>
<div><label>Телефон</label><input class="form-control" name="phone" value="{{$organization->phone}}"></div>
<div><label>Контактное лицо</label><input class="form-control" name="contact_person" value="{{$organization->organizationProfile?->contact_person}}"></div>
<div><label>Должность</label><input class="form-control" name="contact_position" value="{{$organization->organizationProfile?->contact_position}}"></div>
<div><label>№ договора</label><input class="form-control" name="contract_number" value="{{$organization->organizationProfile?->contract_number}}"></div>
<div><label>Дата договора</label><input class="form-control" type="date" name="contract_date" value="{{$organization->organizationProfile?->contract_date?->format('Y-m-d')}}"></div>
</div>
<label class="mt-3">Юридический адрес</label><input class="form-control" name="legal_address" value="{{$organization->organizationProfile?->legal_address}}">
<label class="mt-3">Фактический адрес</label><input class="form-control" name="actual_address" value="{{$organization->organizationProfile?->actual_address}}">
<label class="mt-3">Комментарий</label><textarea class="form-control" name="comment">{{$organization->organizationProfile?->comment}}</textarea>
<button class="btn btn-primary mt-3">Сохранить</button></form></div>

<div class="panel mb-4"><h2>Добавить сотрудников</h2><form method="post" action="{{route('admin.organizations.employees.store',$organization)}}">@csrf
<div class="grid"><div><label>Программа</label><select class="form-select" name="program_id" required>@foreach($programs as $p)<option value="{{$p->id}}">{{$p->title}}@if($p->price!==null) · {{number_format((float)$p->price,0,',',' ')}} ₽@endif</option>@endforeach</select></div>
<div><label>Группа</label><select class="form-select" name="group_id"><option value="">Без группы</option>@foreach($groups as $g)<option value="{{$g->id}}">{{$g->name}}</option>@endforeach</select></div>
<div><label>Начало</label><input class="form-control" type="date" name="started_at" value="{{now()->toDateString()}}"></div>
<div><label>Окончание</label><input class="form-control" type="date" name="ends_at"></div></div>
<label class="mt-3">ФИО — по одному на строку</label><textarea class="form-control" name="names" rows="7" required></textarea>
<button class="btn btn-primary mt-3">Добавить сотрудников</button></form></div>

<div class="panel"><h2>Сотрудники</h2>@forelse($organization->employees as $employee)
<div class="border-top py-3"><form method="post" action="{{route('admin.organizations.employees.update',[$organization,$employee])}}">@csrf @method('PUT')
<div class="grid"><div><label>ФИО</label><input class="form-control" name="full_name" value="{{$employee->full_name}}" required></div><div><label>E-mail</label><input class="form-control" type="email" name="email" value="{{$employee->email}}"></div><div><label>Телефон</label><input class="form-control" name="phone" value="{{$employee->phone}}"></div><div><label><input type="checkbox" name="is_active" value="1" @checked($employee->is_active)> Активен</label></div></div>
<div class="small text-secondary mt-2">@foreach($employee->enrollments as $e)<span class="badge-soft">{{$e->program?->title}}@if($e->group) · {{$e->group->name}}@endif</span> @endforeach</div>
<button class="btn btn-sm btn-outline-primary mt-2">Сохранить</button></form><form class="mt-2" method="post" action="{{route('admin.organizations.employees.destroy',[$organization,$employee])}}">@csrf @method('DELETE')<button class="btn btn-sm btn-outline-danger">Удалить</button></form></div>
@empty<p class="empty">Сотрудников пока нет.</p>@endforelse</div>
@endsection