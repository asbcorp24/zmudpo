@extends('layouts.app')
@section('title','Типы специальностей')
@section('content')
<div class="card">
    <div style="display:flex;justify-content:space-between;align-items:center;gap:12px;flex-wrap:wrap">
        <div>
            <h2 style="margin:0 0 6px">Типы специальностей</h2>
            <div style="color:#667085">Эти направления используются на экране выбора специальности и в карточке программы.</div>
        </div>
        <a class="btn gray" href="{{ route('admin.programs.index') }}">← К программам</a>
    </div>
</div>

<div class="card">
    <h3 style="margin-top:0">Добавить тип</h3>
    <form method="post" action="{{ route('admin.program-types.store') }}" style="display:grid;grid-template-columns:minmax(240px,1fr) auto;gap:12px;align-items:end">
        @csrf
        <div>
            <label>Название</label>
            <input name="name" required maxlength="255" placeholder="Например: Повышение квалификации">
        </div>
        <button class="btn">Добавить</button>
    </form>
</div>

<div class="card">
    <table>
        <thead>
        <tr><th>Название</th><th>Legacy ID</th><th>Программ</th><th>Статус</th><th>Действия</th></tr>
        </thead>
        <tbody>
        @forelse($items as $type)
            <tr>
                <td style="min-width:280px">
                    <form id="type-{{ $type->id }}" method="post" action="{{ route('admin.program-types.update',$type) }}">
                        @csrf @method('PUT')
                        <input name="name" value="{{ $type->name }}" required maxlength="255">
                    </form>
                </td>
                <td>{{ $type->legacy_id ?? '—' }}</td>
                <td>{{ $type->programs_count }}</td>
                <td>
                    <label style="display:flex;align-items:center;gap:8px;margin:0;font-weight:400">
                        <input form="type-{{ $type->id }}" type="checkbox" name="is_active" value="1" @checked($type->is_active) style="width:auto">
                        {{ $type->is_active ? 'Активен' : 'Отключён' }}
                    </label>
                </td>
                <td style="white-space:nowrap">
                    <button form="type-{{ $type->id }}" class="btn">Сохранить</button>
                    <form method="post" action="{{ route('admin.program-types.destroy',$type) }}" style="display:inline" onsubmit="return confirm('Удалить этот тип специальности?')">
                        @csrf @method('DELETE')
                        <button class="btn red" @disabled($type->programs_count>0) title="@if($type->programs_count>0)Сначала переназначьте программы@endif">Удалить</button>
                    </form>
                </td>
            </tr>
        @empty
            <tr><td colspan="5">Типы специальностей пока не созданы.</td></tr>
        @endforelse
        </tbody>
    </table>
</div>
@endsection
