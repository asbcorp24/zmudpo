@extends('layouts.app')
@section('title',$section->title)
@section('page-title','Учебный раздел')
@section('content')
<div class="page-heading"><div><a class="back-link" href="{{ route('learning.index') }}">← Все разделы</a><h1>{{ $section->title }}</h1><p>{{ $section->description }}</p></div><div class="badge-soft">{{ number_format((float)$progress->progress_percent,0) }}% пройдено</div></div>
@if(!auth()->user()->personal_data_consent_at)
<div class="alert alert-warning d-flex justify-content-between align-items-center gap-3 flex-wrap">
 <div><strong>Согласие на обработку персональных данных</strong><div class="small">Для продолжения обучения подтвердите согласие.</div></div>
 <form method="post" action="{{route('learning.personal-data.accept')}}">@csrf<button class="btn btn-warning">Подтвердить</button></form>
</div>
@else
<div class="panel mb-4">
 <div class="panel-head"><div><h2>Присутствие на занятии</h2><p class="text-secondary mb-0">Аналог отметки посещения раздела в старом NMO.</p></div>
 <form method="post" action="{{route('learning.visit',$section)}}">@csrf<button class="btn btn-outline-primary">Я присутствую</button></form></div>
 @if($sectionVisits->isNotEmpty())<div class="d-flex gap-2 flex-wrap">@foreach($sectionVisits as $visit)<span class="badge-soft">{{$visit->visited_at?->format('d.m.Y H:i:s')}}</span>@endforeach</div>@endif
</div>
@if($announcements->isNotEmpty())
<div class="panel mb-4"><div class="panel-head"><h2>Объявления</h2></div>
@foreach($announcements as $a)<div class="list-row"><div><strong>{{$a->title}}</strong><small>{{$a->body}}@if($a->author) · {{$a->author->full_name}}@endif</small></div>
@if(!$acknowledged->contains($a->id))<form method="post" action="{{route('learning.announcement.ack',$a)}}">@csrf<button class="btn btn-sm btn-primary">Прочитано</button></form>@else<span class="status completed">прочитано</span>@endif</div>@endforeach
</div>
@endif
@if($instructorAssignments->isNotEmpty())
<div class="panel mb-4"><div class="panel-head"><h2>Занятия с преподавателем</h2><a href="{{route('student.schedule')}}">Полное расписание</a></div>
@foreach($instructorAssignments as $assignment)<div class="mb-3"><strong>{{$assignment->instructor?->full_name}}</strong>@if($assignment->subject)<span class="text-secondary"> · {{$assignment->subject}}</span>@endif
<div class="d-flex gap-2 flex-wrap mt-2">@foreach($assignment->slots->take(8) as $slot)@php($cnt=(int)($slotCounts[$slot->id]??0))@php($booked=$bookedSlotIds->contains($slot->id))
@if($booked)<form method="post" action="{{route('student.schedule.cancel',$slot)}}">@csrf @method('DELETE')<button class="btn btn-sm btn-success">{{$slot->date?->format('d.m')}} {{$slot->time}} · записан</button></form>
@else<form method="post" action="{{route('student.schedule.book',$slot)}}">@csrf<button class="btn btn-sm btn-outline-primary" @disabled($slot->capacity && $cnt >= $slot->capacity)>{{$slot->date?->format('d.m')}} {{$slot->time}} @if($slot->capacity)({{$cnt}}/{{$slot->capacity}})@endif</button></form>@endif
@endforeach</div></div>@endforeach
</div>
@endif
<div class="panel"><div class="panel-head"><h2>Материалы</h2><div class="btn-group btn-group-sm" role="group"><button type="button" class="btn btn-outline-secondary active" data-nmo-filter="all">Лекции / практика</button><button type="button" class="btn btn-outline-secondary" data-nmo-filter="lecture">Лекции</button><button type="button" class="btn btn-outline-secondary" data-nmo-filter="practice">Практика</button></div></div>
@forelse($section->contentItems as $item)
@php($set=$item->settings?:[])
@php($packageRoot=$set['package_root']??null)
@php($packageEntry=$packageRoot?ltrim(str_replace($packageRoot.'/','',(string)$item->file_path),'/'):null)
@php($response=$responses->get($item->id))
@php($codeLocked=!empty($set['password_required'])&&!session('content_unlocked.'.$item->id))
@php($paymentItem=$paymentTargets->get($item->id))
@php($paymentLocked=$paymentItem&&!$entitledItemIds->contains($item->id))
@php($locked=$codeLocked||$paymentLocked)
<article class="content-item" data-lesson-kind="{{data_get($set,'lesson_kind','all')}}"><div class="content-icon">{{ strtoupper(substr($item->type ?? 'M',0,2)) }}</div><div style="width:100%"><h3>{{ $item->title }}</h3>
@if($paymentLocked)
<div class="response-box mt-3"><div class="d-flex justify-content-between gap-3 align-items-center flex-wrap"><div><strong>Платный учебный блок</strong><div class="text-secondary small">Для доступа требуется оплата@if(data_get($paymentItem->settings,'price')) · {{number_format((float)data_get($paymentItem->settings,'price'),2,',',' ')}} ₽@endif</div></div><form method="post" action="{{route('payments.content.start',$paymentItem)}}" data-no-ajax>@csrf<button class="btn btn-primary" type="submit">Оплатить и открыть</button></form></div></div>
@elseif($codeLocked)
<div class="response-box mt-3"><strong>Материал защищён кодом доступа</strong><form method="post" action="{{route('learning.unlock',$item)}}" class="mt-2">@csrf<div class="input-group"><input class="form-control" name="code" required><button class="btn btn-primary" type="submit">Открыть</button></div></form></div>
@else
@if($item->body)<div class="content-body">{!! $item->body !!}</div>@endif
@if((int)$item->legacy_type===2)
  @if($item->file_path)
    <div class="ratio ratio-16x9 mt-3"><video controls preload="metadata" src="{{route('learning.media',$item)}}" style="width:100%;height:100%;background:#000"></video></div>
  @elseif($item->external_url)
    <div class="mt-3"><a class="btn btn-primary" target="_blank" rel="noopener" href="{{$item->external_url}}">Посмотреть видео ↗</a></div>
  @endif
@endif
@if(!empty($set['list_options']))<div class="mt-2"><strong>Варианты:</strong><ol>@foreach($set['list_options'] as $option)<li>{{$option}}</li>@endforeach</ol></div>@endif
@if(in_array((int)$item->legacy_type,[15,22],true) && (!empty($set['certificate_name'])||!empty($set['certificate_hours'])))<div class="alert alert-info mt-2"><strong>{{$set['certificate_name']??'Сертификат'}}</strong>@if(!empty($set['certificate_hours'])) · {{$set['certificate_hours']}} ч.@endif @if(!empty($set['certificate_text']))<div>{{$set['certificate_text']}}</div>@endif</div>@endif
<div class="d-flex gap-2 flex-wrap mt-2">
@if(in_array((int)$item->legacy_type,[3,15,18,21,22],true) && data_get($set,'quiz_assignment_id'))
@php($itemAttempts=$quizAttempts->get((int)data_get($set,'quiz_assignment_id'),collect()))
@php($lastItemAttempt=$itemAttempts->first())
<a class="btn btn-primary" href="{{route('quizzes.show',data_get($set,'quiz_assignment_id'))}}">{{$lastItemAttempt?'Пройти ещё раз':'Пройти тест'}}</a>
@if($lastItemAttempt)<span class="badge-soft">{{$lastItemAttempt->passed?'Тест пройден':'Тест не пройден'}} · {{number_format((float)$lastItemAttempt->percent,1)}}% · попыток {{$itemAttempts->count()}}@if($item->repeat_limit) / {{$item->repeat_limit}}@endif</span>@endif
@endif
@if((int)$item->legacy_type===15 && data_get($set,'quiz_assignment_id') && optional($quizAttempts->get((int)data_get($set,'quiz_assignment_id')))->contains('passed',true))
<a class="btn btn-success" target="_blank" data-no-ajax href="{{route('learning.certificate',$item)}}">Скачать сертификат</a>
@endif
@if((int)$item->legacy_type===21 && $item->extra_file_path && data_get($set,'quiz_assignment_id') && optional($quizAttempts->get((int)data_get($set,'quiz_assignment_id')))->contains('passed',true))
<a class="btn btn-outline-success" data-no-ajax href="{{route('learning.extra',$item)}}">Просмотреть ответы</a>
@endif
@if($packageRoot && $packageEntry)<a class="btn btn-primary" target="_blank" rel="noopener" href="{{ route('learning.package',['item'=>$item,'path'=>$packageEntry]) }}">Открыть интерактивный материал ↗</a>
@elseif($item->file_path && (int)$item->legacy_type!==2)<a class="btn btn-outline-primary" data-no-ajax href="{{ route('learning.download',$item) }}">Скачать материал</a>@endif
@if((int)$item->legacy_type===19 && $item->file_path)<a class="btn btn-primary" href="{{route('learning.table',$item)}}">Открыть таблицу</a>@endif
@if($item->external_url)<a class="btn btn-outline-primary" target="_blank" rel="noopener" href="{{ $item->external_url }}">Открыть ресурс ↗</a>@endif
@if((int)$item->legacy_type===6)<a class="btn btn-primary" href="{{route('student.surveys',array_filter(['survey'=>data_get($set,'survey_id')]))}}">Перейти к анкете</a>@endif
@if((int)$item->legacy_type===16)<a class="btn btn-primary" href="{{route('learning.practice-diary',$item)}}">Перейти к заполнению дневника</a>@endif
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
@if(in_array((int)$item->legacy_type,[4,10,17],true))
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
@php($standaloneQuizzes=$quizzes->reject(fn($q)=>$linkedQuizIds->contains($q->id)))
@if($standaloneQuizzes->isNotEmpty())<div class="panel mt-4"><div class="panel-head"><h2>Тесты раздела</h2></div>@foreach($standaloneQuizzes as $quiz) @php($last=$quizAttempts->get($quiz->id)?->first()) <div class="list-row"><div><strong>{{ $quiz->title }}</strong><small>Проходной балл {{ $quiz->pass_percent }}%@if($last) · последняя попытка {{ number_format($last->percent,0) }}%@endif</small></div><a class="btn btn-sm btn-primary" href="{{ route('quizzes.show',$quiz) }}">{{ $last?'Повторить':'Пройти' }}</a></div>@endforeach</div>@endif
@if($practice->isNotEmpty())<div class="panel mt-4"><div class="panel-head"><h2>Практические задания</h2></div>@foreach($practice as $a) @php($submission=$submissions->get($a->id)) <div class="list-row"><div><strong>{{ $a->title }}</strong><small>{{ $submission?->status ?: 'не отправлено' }}@if($a->ends_at) · срок {{ $a->ends_at->format('d.m.Y') }}@endif</small></div><a class="btn btn-sm btn-primary" href="{{ route('practice.index') }}">Открыть</a></div>@endforeach</div>@endif
@if(!$progress->completed_at)<form class="mt-3" method="post" action="{{ route('learning.complete',$section) }}">@csrf<button class="btn btn-success">Отметить раздел пройденным</button></form>@else<div class="alert alert-success mt-3">Раздел завершён {{ $progress->completed_at->format('d.m.Y H:i') }}.</div>@endif
<script>
(()=>{const buttons=document.querySelectorAll('[data-nmo-filter]'),items=document.querySelectorAll('[data-lesson-kind]');buttons.forEach(btn=>btn.addEventListener('click',()=>{const mode=btn.dataset.nmoFilter;buttons.forEach(x=>x.classList.toggle('active',x===btn));items.forEach(item=>{const kind=item.dataset.lessonKind||'all';item.hidden=!(mode==='all'||kind==='all'||kind===mode)})}))})();
</script>
@endif
@endsection