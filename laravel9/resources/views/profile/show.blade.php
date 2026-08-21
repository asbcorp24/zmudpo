@extends('layouts.app')
@section('title','Профиль')
@section('content')
<div class="page-heading"><div><h1>Мой профиль</h1><p>Контактные данные и адрес для отправки документов.</p></div></div>
<form method="post" action="{{ route('profile.update') }}" class="panel">@csrf @method('put')
<div class="grid">
<div><label>ФИО</label><input name="full_name" value="{{ old('full_name',$user->full_name) }}" required></div>
<div><label>Email</label><input type="email" name="email" value="{{ old('email',$user->email) }}"></div>
<div><label>Телефон</label><input name="phone" value="{{ old('phone',$user->phone) }}"></div>
<div><label>Индекс</label><input name="postal_code" value="{{ old('postal_code',$user->mailingAddress?->postal_code) }}"></div>
<div><label>Регион</label><input name="region" value="{{ old('region',$user->mailingAddress?->region) }}"></div>
<div><label>Район</label><input name="district" value="{{ old('district',$user->mailingAddress?->district) }}"></div>
<div><label>Город / населённый пункт</label><input name="city" value="{{ old('city',$user->mailingAddress?->city) }}"></div>
<div><label>Улица</label><input name="street" value="{{ old('street',$user->mailingAddress?->street) }}"></div>
<div><label>Дом</label><input name="house" value="{{ old('house',$user->mailingAddress?->house) }}"></div>
<div><label>Квартира</label><input name="apartment" value="{{ old('apartment',$user->mailingAddress?->apartment) }}"></div>
</div>
<label>Комментарий к адресу</label><textarea name="address_comment" rows="3">{{ old('address_comment',$user->mailingAddress?->comment) }}</textarea>
<div style="margin-top:16px"><button class="btn">Сохранить профиль</button></div>
</form>
@endsection
