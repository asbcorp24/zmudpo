@extends('layouts.public')
@section('title','E-mail подтверждён')
@section('content')
<section class="detail-hero"><div class="container"><div class="row justify-content-center"><div class="col-lg-8"><div class="detail-card text-center p-5"><div class="display-3 mb-3">✓</div><span class="hero-kicker">E-mail подтверждён</span><h1 class="mt-3">Спасибо, {{$user->full_name}}</h1><p class="lead text-secondary">Заявка подтверждена. Теперь она ожидает обработки администратором. После активации на вашу почту придёт безопасная ссылка для установки пароля.</p><a class="btn btn-primary btn-lg mt-2" href="{{route('home')}}">На главную</a></div></div></div></div></section>
@endsection
