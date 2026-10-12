@extends('layouts.public')
@section('title','Новый пароль')
@section('content')
<div class="container py-5" style="max-width:640px"><div class="detail-card"><h1>Установить пароль</h1>
@if($errors->any())<div class="alert alert-danger">{{$errors->first()}}</div>@endif
<form id="resetForm" method="post" action="{{route('password.update')}}">@csrf
<input type="hidden" name="token" value="{{$token}}"><input type="hidden" name="email" value="{{$email}}">
<button id="setPassword" type="button" class="btn btn-primary">Установить новый пароль</button>
</form></div></div>
<script>
document.getElementById('setPassword').addEventListener('click',()=>{const a=prompt('Введите новый пароль (минимум 8 символов)');if(!a)return;const b=prompt('Повторите новый пароль');if(a!==b){alert('Пароли не совпадают');return}const f=document.getElementById('resetForm');[['password',a],['password_confirmation',b]].forEach(([n,v])=>{const i=document.createElement('input');i.type='hidden';i.name=n;i.value=v;f.appendChild(i)});f.submit()});
</script>
@endsection
