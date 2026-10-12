@extends('layouts.app')
@section('title','Шаблоны документов')
@section('content')
<div class="page-heading"><div><h1>Шаблоны документов</h1><p>Удостоверения, дипломы, сертификаты и выписки.</p></div></div>

<div class="panel mb-4"><h2>Новый шаблон</h2>
<form method="post" action="{{route('admin.document-templates.store')}}">@csrf
<div class="grid">
<div><label>Название</label><input class="form-control" name="name" required></div>
<div><label>Тип</label><select class="form-select" name="document_type"><option value="certificate">Удостоверение</option><option value="diploma">Диплом</option><option value="certificate_nmo">Сертификат НМО</option><option value="statement">Выписка</option><option value="other">Другой</option></select></div>
<div><label>Ориентация</label><select class="form-select" name="orientation"><option value="landscape">Альбомная</option><option value="portrait">Книжная</option></select></div>
</div>
<div class="grid mt-3"><div><label>Организация</label><input class="form-control" name="organization_name" value="ГАПОУ «Зеленодольский медицинский колледж»"></div><div><label>Заголовок</label><input class="form-control" name="heading"></div><div><label>Подзаголовок</label><input class="form-control" name="subheading"></div></div>
<label class="mt-3">Текст</label><textarea class="form-control" name="body_template" rows="4" placeholder="Поддерживаются: {name}, {program}, {hours}, {qualification}, {number}, {reg_number}, {date}, {protocol}">{name} успешно завершил(а) обучение по программе «{program}» в объёме {hours} часов.</textarea>
<div class="grid mt-3"><div><label>Левая подпись</label><input class="form-control" name="footer_left" value="Руководитель __________________"></div><div><label>Правая подпись</label><input class="form-control" name="footer_right" value="М.П."></div></div>
<label class="me-3 mt-3"><input type="checkbox" name="is_default" value="1"> По умолчанию</label><label><input type="checkbox" name="is_active" value="1" checked> Активен</label>
<div><button class="btn btn-primary mt-3">Создать шаблон</button></div>
</form></div>

@foreach($items as $x)
<div class="panel mb-4"><form method="post" action="{{route('admin.document-templates.update',$x)}}">@csrf @method('PUT')
<div class="d-flex justify-content-between gap-2 flex-wrap"><h2>{{$x->name}}</h2><div><a class="btn btn-sm btn-outline-primary" target="_blank" data-no-ajax href="{{route('admin.document-templates.preview',$x)}}">Предпросмотр</a></div></div>
<div class="grid"><div><label>Название</label><input class="form-control" name="name" value="{{$x->name}}" required></div><div><label>Тип</label><select class="form-select" name="document_type">@foreach(['certificate'=>'Удостоверение','diploma'=>'Диплом','certificate_nmo'=>'Сертификат НМО','statement'=>'Выписка','other'=>'Другой'] as $k=>$v)<option value="{{$k}}" @selected($x->document_type===$k)>{{$v}}</option>@endforeach</select></div><div><label>Ориентация</label><select class="form-select" name="orientation"><option value="landscape" @selected($x->orientation==='landscape')>Альбомная</option><option value="portrait" @selected($x->orientation==='portrait')>Книжная</option></select></div></div>
<div class="grid mt-3"><div><label>Организация</label><input class="form-control" name="organization_name" value="{{$x->organization_name}}"></div><div><label>Заголовок</label><input class="form-control" name="heading" value="{{$x->heading}}"></div><div><label>Подзаголовок</label><input class="form-control" name="subheading" value="{{$x->subheading}}"></div></div>
<label class="mt-3">Текст</label><textarea class="form-control" name="body_template" rows="4">{{$x->body_template}}</textarea>
<div class="grid mt-3"><div><label>Левая подпись</label><input class="form-control" name="footer_left" value="{{$x->footer_left}}"></div><div><label>Правая подпись</label><input class="form-control" name="footer_right" value="{{$x->footer_right}}"></div></div>
<label class="me-3 mt-3"><input type="checkbox" name="is_default" value="1" @checked($x->is_default)> По умолчанию</label><label><input type="checkbox" name="is_active" value="1" @checked($x->is_active)> Активен</label>
<button class="btn btn-primary mt-3">Сохранить</button>
</form>
<form method="post" action="{{route('admin.document-templates.destroy',$x)}}" class="mt-2">@csrf @method('DELETE')<button class="btn btn-sm btn-outline-danger">Удалить</button></form>
</div>
@endforeach
@endsection