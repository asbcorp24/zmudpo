@extends('layouts.app')
@section('title','Выдача документов')
@section('content')
<div class="page-heading"><div><h1>Выдача документов</h1><p>Удостоверения, дипломы, сертификаты и печатные формы после завершения обучения.</p></div></div>

<div class="panel mb-4">
 <div class="panel-head"><h2>Выдать документ</h2></div>
 <form method="post" action="{{route('admin.documents.store')}}" enctype="multipart/form-data">@csrf
  <div class="grid">
   <div><label>Слушатель / программа</label><select class="form-select" id="enr" required><option value="">Выберите...</option>@foreach($completed as $e)<option data-u="{{$e->user_id}}" data-p="{{$e->program_id}}">{{$e->user?->full_name}} — {{$e->program?->title}}</option>@endforeach</select><input type="hidden" id="uid" name="user_id"><input type="hidden" id="pid" name="program_id"></div>
   <div><label>Тип</label><select class="form-select" name="type"><option value="certificate">Удостоверение</option><option value="diploma">Диплом</option><option value="certificate_nmo">Сертификат НМО</option><option value="statement">Выписка</option><option value="other">Другой документ</option></select></div>
   <div><label>Название</label><input class="form-control" name="title" required value="Документ об обучении"></div>
   <div><label>Номер документа</label><input class="form-control" name="number"></div>
   <div><label>Регистрационный номер</label><input class="form-control" name="registration_number"></div>
   <div><label>Дата выдачи</label><input class="form-control" type="date" name="issued_at" value="{{now()->toDateString()}}"></div>
   <div><label>Часы</label><input class="form-control" type="number" min="0" name="hours"></div>
   <div><label>Протокол</label><input class="form-control" name="protocol"></div>
   <div><label>Квалификация</label><input class="form-control" name="qualification"></div>
   <div><label>Готовый файл (необязательно)</label><input class="form-control" type="file" name="file"></div>
  </div>
  <button class="btn btn-primary mt-3">Выдать документ</button>
 </form>
</div>

<div class="panel">
 <div class="table-responsive"><table class="table table-hover align-middle">
  <thead><tr><th>Слушатель</th><th>Программа</th><th>Документ</th><th>Номер</th><th>Дата</th><th></th></tr></thead>
  <tbody>@foreach($items as $x)<tr>
   <td>{{$x->user?->full_name??'Общий документ'}}</td><td>{{$x->program?->title??'—'}}</td>
   <td><span class="badge-soft">{{$x->type}}</span><div>{{$x->title}}</div></td><td>{{$x->number??'—'}}</td><td>{{$x->issued_at?->format('d.m.Y')}}</td>
   <td><div class="d-flex gap-2 flex-wrap"><a class="btn btn-sm btn-outline-primary" target="_blank" data-no-ajax href="{{route('admin.documents.print',$x)}}">Печатная форма</a>@if($x->file_path)<a class="btn btn-sm btn-outline-secondary" data-no-ajax href="{{route('documents.download',$x)}}">Файл</a>@endif<form method="post" action="{{route('admin.documents.destroy',$x)}}">@csrf @method('DELETE')<button class="btn btn-sm btn-outline-danger">Отозвать</button></form></div></td>
  </tr>@endforeach</tbody>
 </table></div>{{$items->links()}}
</div>
@push('scripts')
<script>document.addEventListener('change',e=>{if(e.target.id!=='enr')return;const o=e.target.options[e.target.selectedIndex];document.getElementById('uid').value=o.dataset.u||'';document.getElementById('pid').value=o.dataset.p||'';});</script>
@endpush
@endsection
