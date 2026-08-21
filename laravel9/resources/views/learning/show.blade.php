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
<article class="content-item"><div class="content-icon">{{ strtoupper(substr($item->type ?? 'M',0,2)) }}</div><div style="width:100%"><h3>{{ $item->title }}</h3>
@if($item->body)<div class="content-body">{!! $item->body !!}</div>@endif
@if(!empty($set['list_options']))<div class="mt-2"><strong>Варианты:</strong><ol>@foreach($set['list_options'] as $option)<li>{{$option}}</li>@endforeach</ol></div>@endif
@if(in_array((int)$item->legacy_type,[15,22],true) && (!empty($set['certificate_name'])||!empty($set['certificate_hours'])))<div class="alert alert-info mt-2"><strong>{{$set['certificate_name']??'Сертификат'}}</strong>@if(!empty($set['certificate_hours'])) · {{$set['certificate_hours']}} ч.@endif @if(!empty($set['certificate_text']))<div>{{$set['certificate_text']}}</div>@endif</div>@endif
<div class="d-flex gap-2 flex-wrap mt-2">
@if($packageRoot && $packageEntry)<a class="btn btn-primary" target="_blank" rel="noopener" href="{{ route('learning.package',['item'=>$item,'path'=>$packageEntry]) }}">Открыть интерактивный материал ↗</a>
@elseif($item->file_path)<a class="btn btn-outline-primary" href="{{ route('learning.download',$item) }}">Скачать материал</a>@endif
@if($item->external_url)<a class="btn btn-outline-primary" target="_blank" rel="noopener" href="{{ $item->external_url }}">Открыть ресурс ↗</a>@endif
@if((int)$item->legacy_type===6)<a class="btn btn-primary" href="{{route('student.surveys')}}">Перейти к анкете</a>@endif
@if((int)$item->legacy_type===16)<a class="btn btn-primary" href="{{route('practice.index')}}">Перейти к практике</a>@endif
@if((int)$item->legacy_type===11)<span class="badge-soft">Оплата@if(isset($set['price'])): {{number_format((float)$set['price'],2,',',' ')}} ₽@endif</span>@endif
@if((int)$item->legacy_type===20)<button class="btn btn-outline-primary random-number" type="button" data-min="{{$set['random_min']??1}}" data-max="{{$set['random_max']??100}}">Сгенерировать число</button><strong class="random-result"></strong>@endif
</div></div></article>
@empty<p class="empty">Материалы пока не добавлены или сейчас недоступны.</p>@endforelse</div>
@if($quizzes->isNotEmpty())<div class="panel mt-4"><div class="panel-head"><h2>Тесты раздела</h2></div>@foreach($quizzes as $quiz) @php($last=$quizAttempts->get($quiz->id)?->first()) <div class="list-row"><div><strong>{{ $quiz->title }}</strong><small>Проходной балл {{ $quiz->pass_percent }}%@if($last) · последняя попытка {{ number_format($last->percent,0) }}%@endif</small></div><a class="btn btn-sm btn-primary" href="{{ route('quizzes.show',$quiz) }}">{{ $last?'Повторить':'Пройти' }}</a></div>@endforeach</div>@endif
@if($practice->isNotEmpty())<div class="panel mt-4"><div class="panel-head"><h2>Практические задания</h2></div>@foreach($practice as $a) @php($submission=$submissions->get($a->id)) <div class="list-row"><div><strong>{{ $a->title }}</strong><small>{{ $submission?->status ?: 'не отправлено' }}@if($a->ends_at) · срок {{ $a->ends_at->format('d.m.Y') }}@endif</small></div><a class="btn btn-sm btn-primary" href="{{ route('practice.index') }}">Открыть</a></div>@endforeach</div>@endif
@if(!$progress->completed_at)<form class="mt-3" method="post" action="{{ route('learning.complete',$section) }}">@csrf<button class="btn btn-success">Отметить раздел пройденным</button></form>@else<div class="alert alert-success mt-3">Раздел завершён {{ $progress->completed_at->format('d.m.Y H:i') }}.</div>@endif
<script>document.addEventListener('click',e=>{const b=e.target.closest('.random-number');if(!b)return;let min=parseInt(b.dataset.min||'1',10),max=parseInt(b.dataset.max||'100',10);if(max<min)[min,max]=[max,min];const n=Math.floor(Math.random()*(max-min+1))+min;b.parentElement.querySelector('.random-result').textContent=' '+n;});</script>
@endsection