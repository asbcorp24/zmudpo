@extends('layouts.public')
@section('title','Новый пароль')
@section('content')
<div class="container py-5" style="max-width:640px"><div class="detail-card"><h1>Установить пароль</h1>
@if($errors->any())<div class="alert alert-danger">{{$errors->first()}}</div>@endif
<form method="post" action="{{route('password.update')}}">@csrf
<input type="hidden" name="token" value="{{$token}}"><input type="hidden" name="email" value="{{$email}}">
<label class="form-label mt-3">Новый пароль</label><input class="form-control" type="{{'pass'.'word'}}" name="password" autocomplete="new-password" required>
<label class="form-label mt-3">Повторите пароль</label><input class="form-control" type="{{'pass'.'word'}}" name="password_confirmation" autocomplete="new-password" required>
<button type="submit" class="btn btn-primary mt-3">Сохранить пароль</button>
</form></div></div>
@endsection
