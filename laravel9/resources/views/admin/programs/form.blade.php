@extends('layouts.app')
@section('title',$item->exists?'Редактирование программы':'Новая специальность / программа')
@section('content')
<div class="card">
<div style="display:flex;justify-content:space-between;gap:12px;align-items:center;flex-wrap:wrap;margin-bottom:12px">
    <div style="color:#667085">Создайте программу и укажите её направление из списка типов специальностей.</div>
    <a class="btn gray" href="{{ route('admin.program-types.index') }}">Управление типами специальностей</a>
</div>
<form method="post" action="{{ $item->exists?route('admin.programs.update',$item):route('admin.programs.store') }}">
@csrf @if($item->exists)@method('PUT')@endif
<label>Название специальности / программы</label><input name="title" value="{{old('title',$item->title)}}" required>
<div class="grid">
<div><label>Режим</label><select name="mode"><option value="dpo" @selected($item->mode==='dpo')>ДПО</option><option value="nmo" @selected($item->mode==='nmo')>НМО</option></select></div>
<div><label>Тип специальности</label><select name="program_type_id"><option value="">— без типа —</option>@foreach($types as $x)<option value="{{$x->id}}" @selected($item->program_type_id==$x->id)>{{$x->name}}{{ !$x->is_active?' (отключён)':'' }}</option>@endforeach</select></div>
<div><label>Шаблон сертификата</label><select name="certificate_template_id"><option value="">—</option>@foreach($certificates as $x)<option value="{{$x->id}}" @selected($item->certificate_template_id==$x->id)>{{$x->name}}</option>@endforeach</select></div>
<div><label>Категория</label><input name="category" value="{{old('category',$item->category)}}"></div>
<div><label>Часы</label><input type="number" name="hours" value="{{old('hours',$item->hours)}}"></div>
<div><label>Стоимость, ₽</label><input type="number" min="0" step="0.01" name="price" value="{{old('price',$item->price)}}"></div>
<div><label>Начало</label><input type="date" name="starts_at" value="{{optional($item->starts_at)->format('Y-m-d')}}"></div>
<div><label>Окончание</label><input type="date" name="ends_at" value="{{optional($item->ends_at)->format('Y-m-d')}}"></div>
</div>
<label>Краткое описание для каталога</label><textarea name="short_description" rows="3">{{old('short_description',$item->short_description)}}</textarea>
<label>Полное описание</label><textarea name="about" rows="8">{{old('about',$item->about)}}</textarea>
<div class="grid"><label><input style="width:auto" type="checkbox" name="is_active" value="1" @checked(!$item->exists||$item->is_active)> Активна</label><label><input style="width:auto" type="checkbox" name="registration_enabled" value="1" @checked(!$item->exists||$item->registration_enabled)> Разрешена запись</label><label><input style="width:auto" type="checkbox" name="featured" value="1" @checked($item->featured)> Избранная в системе</label><label><input style="width:auto" type="checkbox" name="is_featured_public" value="1" @checked($item->is_featured_public)> Показывать на публичной главной</label></div>
<button class="btn">Сохранить</button>
</form>
</div>
@endsection
