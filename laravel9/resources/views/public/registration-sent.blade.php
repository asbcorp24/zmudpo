@extends('layouts.public')
@section('title','Заявка отправлена')
@section('content')
<section class="detail-hero"><div class="container"><div class="row justify-content-center"><div class="col-lg-8"><div class="detail-card text-center p-5"><div class="display-3 mb-3">✉</div><span class="hero-kicker">Заявка принята</span><h1 class="mt-3">Подтвердите e-mail</h1><p class="lead text-secondary">Мы отправили письмо на <strong>{{$user->email}}</strong>. Перейдите по ссылке в письме. После подтверждения администратор проверит заявку и откроет доступ.</p><p class="text-secondary">Если письмо не видно, проверьте папку «Спам».</p><a class="btn btn-primary btn-lg mt-2" href="{{route('home')}}">На главную</a></div></div></div></div></section>
@endsection
