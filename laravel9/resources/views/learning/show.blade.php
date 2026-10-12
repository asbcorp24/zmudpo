@extends('layouts.app')
@section('title',$section->title)
@section('page-title','Учебный раздел')
@section('content')
<div class="page-heading"><div><a class="back-link" href="{{ route('learning.index') }}">← Все разделы</a><h1>{{ $section->title }}</h1><p>{{ $section->description }}</p></div><div class="badge-soft">{{ number_format((float)$progress->progress_percent,0) }}% пройдено</div></div>
<div class="panel"><div class="panel-head"><h2>Материалы</h2></div>
@forelse($section->contentItems as $item)
@php($set=$item->settings?:[])
@php($packageRoot=$set['package_root']??null)
@php($packageEntry=$packageRoot?ltrim(str_replace($packageRoot.'/','',(string)$item->file_path),'/'):null)
@php($response=$responses->get($item->id))
@php($codeLocked=!empty($set['password_required'])&&!session('content_unlocked.'.$item->id))
@php($paymentItem=$paymentTargets->get($item->id))
@php($paymentLocked=$paymentItem&&!$entitledItemIds->contains($item->id))
@php($locked=$codeLocked||$paymentLocked)
<article class="content-item"><div class="content-icon">{{ strtoupper(substr($item->type ?? 'M',0,2)) }}</div><div style="width:100%"><h3>{{ $item->title }}</h3>
@if($paymentLocked)
<div class="response-box mt-3"><div class="d-flex justify-content-between gap-3 align-items-center flex-wrap"><div><strong>Платный учебный блок</strong><div class="text-secondary small">Для доступа требуется оплата@if(data_get($paymentItem->settings,'price')) · {{number_format((float)data_get($paymentItem->settings,'price'),2,',',' ')}} ₽@endif</div></div><form method="post" action="{{route('payments.content.start',$paymentItem)}}" data-no-ajax>@csrf<button class="btn btn-primary" type="submit">Оплатить и открыть</button></form></div></div>
@elseif($codeLocked)
<div class="response-box mt-3"><strong>Материал защищён кодом доступа</strong><form method="post" action="{{route('learning.unlock',$item)}}" class="mt-2">@csrf<div class="input-group"><input class="form-control" name="code" required><button class="btn btn-primary" type="submit">Открыть</button></div></form></div>
@else
@if($item->body)<div class="content-body">{!! $item->body !!}</div>@endif
@if(!empty($set['list_options']))<div class="mt-2"><strong>Варианты:</strong><ol>@foreach($set['list_options'] as $option)<li>{{$option}}</li>@endforeach</ol></div>@endif
@if(in_array((int)$item->legacy_type,[15,22],true) && (!empty($set['certificate_name'])||!empty($set['certificate_hours'])))<div class="alert alert-info mt-2"><strong>{{$set['certificate_name']??'Сертификат'}}</strong>@if(!empty($set['certificate_hours'])) · {{$set['certificate_hours']}} ч.@endif @if(!empty($set['certificate_text']))<div>{{$set['certificate_text']}}</div>@endif</div>@endif
<div class="d-flex gap-2 flex-wrap mt-2">
@if($packageRoot && $packageEntry)<a class="btn btn-primary" target="_blank" rel="noopener" href="{{ route('learning.package',['item'=>$item,'path'=>$packageEntry]) }}">Открыть интерактивный материал ↗</a>
@elseif($item->file_path)<a class="btn btn-outline-primary" data-no-ajax href="{{ route('learning.download',$item) }}">Скачать материал</a>@endif
@if((int)$item->legacy_type===19 && $item->file_path)<a class="btn btn-primary" href="{{route('learning.table',$item)}}">Открыть таблицу</a>@endif
@if($item->external_url)<a class="btn btn-outline-primary" target="_blank" rel="noopener" href="{{ $item->external_url }}">Открыть ресурс ↗</a>@endif
@if((int)$item->legacy_type===6)<a class="btn btn-primary" href="{{route('student.surveys')}}">Перейти к анкете</a>@endif
@if((int)$item->legacy_type===16)<a class="btn btn-primary" href="{{route('practice.index')}}">Перейти к практике</a>@endif
@if((int)$item->legacy_type===11)<span class="badge-soft">Оплата@if(isset($set['price'])): {{number_format((float)$set['price'],2,',',' ')}} ₽@endif</span>@endif
@if((int)$item->legacy_type===5)<form method="post" action="{{route('learning.complete-program',$item)}}" class="d-inline">@csrf<button class="btn btn-success" type="submit">Завершить обучение</button></form>@endif
@if((int)$item->legacy_type===20)
@if(data_get($response?->meta,'random_value')!==null)
<span class="badge-soft">Ваше значение: <strong>{{data_get($response->meta,'random_value')}}</strong></span>
@else
<form method="post" action="{{route('learning.response',$item)}}" class="d-inline">@csrf<input type="hidden" name="action" value="generate"><button class="btn btn-outline-primary" type="submit">Получить случайное значение</button></form>
@endif
@endif
</div>
@if(in_array((int)$item->legacy_type,[4,7,10,17],true))
<div class="response-box mt-3">
  <div class="d-flex justify-content-between gap-2 align-items-center flex-wrap mb-2">
    <strong>{{ match((int)$item->legacy_type){4=>'Ответ на контрольную',7=>'Файл / ответ',10=>'Решение задания',17=>'Рабочая тетрадь',default=>'Ответ'} }}</strong>
    @if($response)<span class="status {{$response->status==='accepted'?'completed':($response->status==='revision'||$response->status==='rejected'?'blocked':'active')}}">{{$response->status}}</span>@endif
  </div>
  @if($response?->review_comment)<div class="review-note"><strong>Комментарий куратора:</strong> {{$response->review_comment}} @if($response->score!==null)<span class="ms-2">Оценка: {{$response->score}}</span>@endif</div>@endif
  <form method="post" action="{{route('learning.response',$item)}}" enctype="multipart/form-data">@csrf
    @if(in_array((int)$item->legacy_type,[4,10,17],true))
      <label class="form-label mt-2">Текст ответа</label>
      <textarea class="form-control" name="text" rows="4" placeholder="Введите ответ или комментарий">{{$response?->text}}</textarea>
    @endif
    @if((int)$item->legacy_type===10)
      <label class="form-label mt-2">Скриншоты / файлы</label>
      <input class="form-control" type="file" name="files[]" multiple>
      @if(!empty($response?->files))<div class="mt-2 d-flex gap-2 flex-wrap">@foreach($response->files as $i=>$f)<a class="btn btn-sm btn-outline-secondary" data-no-ajax href="{{route('learning.response.download',[$response,$i])}}">Файл {{$i+1}}</a>@endforeach</div>@endif
    @else
      <label class="form-label mt-2">Файл</label>
      <input class="form-control" type="file" name="file">
      @if($response?->file_path)<a class="btn btn-sm btn-outline-secondary mt-2" data-no-ajax href="{{route('learning.response.download',$response)}}">Скачать текущий файл</a>@endif
    @endif
    <button class="btn btn-primary mt-3" type="submit">{{ $response ? 'Обновить и отправить' : 'Отправить куратору' }}</button>
  </form>
</div>
@endif
@endif
</div></article>
@empty<p class="empty">Материалы пока не добавлены или сейчас недоступны.</p>@endforelse</div>
@if($quizzes->isNotEmpty())<div class="panel mt-4"><div class="panel-head"><h2>Тесты раздела</h2></div>@foreach($quizzes as $quiz) @php($last=$quizAttempts->get($quiz->id)?->first()) <div class="list-row"><div><strong>{{ $quiz->title }}</strong><small>Проходной балл {{ $quiz->pass_percent }}%@if($last) · последняя попытка {{ number_format($last->percent,0) }}%@endif</small></div><a class="btn btn-sm btn-primary" href="{{ route('quizzes.show',$quiz) }}">{{ $last?'Повторить':'Пройти' }}</a></div>@endforeach</div>@endif
@if($practice->isNotEmpty())<div class="panel mt-4"><div class="panel-head"><h2>Практические задания</h2></div>@foreach($practice as $a) @php($submission=$submissions->get($a->id)) <div class="list-row"><div><strong>{{ $a->title }}</strong><small>{{ $submission?->status ?: 'не отправлено' }}@if($a->ends_at) · срок {{ $a->ends_at->format('d.m.Y') }}@endif</small></div><a class="btn btn-sm btn-primary" href="{{ route('practice.index') }}">Открыть</a></div>@endforeach</div>@endif
@if(!$progress->completed_at)<form class="mt-3" method="post" action="{{ route('learning.complete',$section) }}">@csrf<button class="btn btn-success">Отметить раздел пройденным</button></form>@else<div class="alert alert-success mt-3">Раздел завершён {{ $progress->completed_at->format('d.m.Y H:i') }}.</div>@endif
@endsection