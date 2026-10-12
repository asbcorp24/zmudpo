@extends('layouts.public')
@section('title','Восстановление пароля')
@section('content')
<div class="container py-5" style="max-width:640px">
 <div class="detail-card">
  <h1 class="h3">Восстановление пароля</h1>
  <p class="text-secondary">Укажите e-mail, привязанный к учётной записи. Мы отправим ссылку для установки нового пароля.</p>
  @if(session('ok'))<div class="alert alert-success">{{session('ok')}}</div>@endif
  @if($errors->any())<div class="alert alert-danger">{{$errors->first()}}</div>@endif
  <form method="post" action="{{route('password.email')}}">@csrf
   <label class="form-label">E-mail</label><input class="form-control form-control-lg" type="email" name="email" value="{{old('email')}}" required>
   <button class="btn btn-primary mt-3">Отправить ссылку</button>
  </form>
 </div>
</div>
@endsection