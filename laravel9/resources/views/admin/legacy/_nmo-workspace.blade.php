@if($program)
@php
$typeNames=collect($legacyTypes)->mapWithKeys(fn($v,$k)=>[$k=>$v['name']]);
@endphp
<div class="nmo-program-head">
  <div>
    <div class="nmo-eyebrow">Текущая специальность</div>
    <h2>{{$program->title}}</h2>
    <div class="nmo-meta"><span>{{strtoupper($program->mode)}}</span>@if($program->type)<span>{{$program->type->name}}</span>@endif @if($program->hours)<span>{{$program->hours}} ч.</span>@endif <span>Разделов: {{$sections->count()}}</span></div>
  </div>
  <div class="nmo-head-actions">
    <a class="btn gray" href="{{route('admin.programs.edit',$program)}}">Настройки программы</a>
    <button class="btn" type="button" data-toggle-panel="createSectionPanel">+ Новый раздел</button>
  </div>
</div>

<div class="nmo-tools">
  <div class="nmo-tool-card">
    <strong>Массовые действия</strong><span>Аналоги кнопок старого add_nmo.php</span>
    <div class="nmo-inline-actions">
      <form data-ajax="1" method="post" action="{{route('admin.nmo.defaults')}}">@csrf<input type="hidden" name="program_id" value="{{$program->id}}"><input type="hidden" name="kind" value="response"><button class="btn gray" type="submit">Добавить «Решение заданий» во все разделы</button></form>
      <form data-ajax="1" method="post" action="{{route('admin.nmo.defaults')}}">@csrf<input type="hidden" name="program_id" value="{{$program->id}}"><input type="hidden" name="kind" value="questionnaire"><select name="survey_id"><option value="">Анкета без привязки</option>@foreach($surveys as $survey)<option value="{{$survey->id}}">{{$survey->title}}</option>@endforeach</select><button class="btn gray" type="submit">Добавить анкету во все разделы</button></form>
    </div>
  </div>
  <div class="nmo-tool-card"><strong>Быстрый поиск</strong><span>Фильтрация разделов и материалов без запроса к серверу</span><input id="nmoFilter" placeholder="Название раздела или материала..."></div>
</div>

<div id="createSectionPanel" class="nmo-collapse" hidden>
  <form data-ajax="1" method="post" action="{{route('admin.nmo.sections.store')}}" enctype="multipart/form-data" class="nmo-form-card">@csrf
    <input type="hidden" name="program_id" value="{{$program->id}}">
    <div class="nmo-form-title"><div><b>Новый раздел</b><span>Аналог добавления tm_nmo_razd</span></div><button type="button" class="nmo-x" data-toggle-panel="createSectionPanel">×</button></div>
    <div class="nmo-grid-3"><div><label>Название</label><input name="title" required></div><div><label>Порядок</label><input type="number" name="position" min="0" value="{{$sections->max('position')+1}}"></div><div><label>Основной преподаватель</label><select name="primary_instructor_id"><option value="">—</option>@foreach($instructors as $i)<option value="{{$i->id}}">{{$i->full_name}}</option>@endforeach</select></div></div>
    <label>Комментарий / описание</label><textarea name="description" rows="3"></textarea>
    <div class="nmo-grid-2"><div><label>Дополнительные преподаватели</label><select name="instructor_ids[]" multiple size="4">@foreach($instructors as $i)<option value="{{$i->id}}">{{$i->full_name}}</option>@endforeach</select></div><div><label>Изображение раздела</label><label class="drop-zone"><input type="file" name="image" accept="image/*"><span>Перетащите изображение сюда<br><small>или нажмите для выбора</small></span></label></div></div>
    <div class="nmo-checks"><label><input type="checkbox" name="is_active" value="1" checked> Активен</label><label><input type="checkbox" name="is_required" value="1"> Обязательный раздел</label></div>
    <div class="upload-progress" hidden><i></i></div><button class="btn" type="submit">Создать раздел</button>
  </form>
</div>

<div id="sectionList" class="nmo-section-list" data-sort-kind="sections">
@forelse($sections as $s)
  @php($primary=$s->instructors->first(fn($x)=>(bool)$x->pivot->is_primary))
  <section class="nmo-section-card" draggable="true" data-sort-id="{{$s->id}}" data-search="{{mb_strtolower($s->title.' '.$s->description.' '.$s->contentItems->pluck('title')->join(' '))}}">
    <div class="nmo-section-bar">
      <span class="drag-handle" title="Перетащить">⋮⋮</span>
      <div class="nmo-section-title"><span class="nmo-order">{{$s->position}}</span><div><h3>{{$s->title}}</h3><div class="nmo-meta">@if($primary)<span>Преподаватель: {{$primary->full_name}}</span>@endif @if($s->instructors->count()>1)<span>+{{$s->instructors->count()-1}} доп.</span>@endif <span>{{$s->contentItems->count()}} элементов</span></div></div></div>
      <div class="nmo-section-status"><span class="status-dot {{$s->is_active?'on':'off'}}"></span>{{$s->is_active?'Активен':'Выключен'}} @if($s->is_required)<span class="chip">обязательный</span>@endif</div>
      <div class="nmo-row-actions"><button type="button" class="icon-btn" data-open-add="{{$s->id}}" title="Добавить материал">＋</button><button type="button" class="icon-btn" data-toggle-panel="sectionEdit{{$s->id}}" title="Редактировать">✎</button></div>
    </div>
    @if($s->description)<div class="nmo-section-desc">{{$s->description}}</div>@endif

    <div id="sectionEdit{{$s->id}}" class="nmo-collapse" hidden>
      <form data-ajax="1" method="post" action="{{route('admin.nmo.sections.update',$s)}}" enctype="multipart/form-data" class="nmo-inline-editor">@csrf @method('PUT')
        <div class="nmo-grid-3"><div><label>Название</label><input name="title" value="{{$s->title}}" required></div><div><label>Порядок</label><input type="number" name="position" value="{{$s->position}}" min="0"></div><div><label>Основной преподаватель</label><select name="primary_instructor_id"><option value="">—</option>@foreach($instructors as $i)<option value="{{$i->id}}" @selected($primary?->id===$i->id)>{{$i->full_name}}</option>@endforeach</select></div></div>
        <label>Описание</label><textarea name="description" rows="3">{{$s->description}}</textarea>
        <div class="nmo-grid-2"><div><label>Дополнительные преподаватели</label><select name="instructor_ids[]" multiple size="4">@foreach($instructors as $i)<option value="{{$i->id}}" @selected($s->instructors->where('pivot.is_primary',false)->contains('id',$i->id))>{{$i->full_name}}</option>@endforeach</select></div><div><label>Заменить изображение</label><label class="drop-zone compact"><input type="file" name="image" accept="image/*"><span>Перетащить изображение</span></label></div></div>
        <div class="nmo-checks"><label><input type="checkbox" name="is_active" value="1" @checked($s->is_active)> Активен</label><label><input type="checkbox" name="is_required" value="1" @checked($s->is_required)> Обязательный</label></div>
        <div class="nmo-inline-actions"><button class="btn" type="submit">Сохранить раздел</button></form>
          <form data-ajax="1" data-confirm="Удалить раздел вместе со всеми материалами?" method="post" action="{{route('admin.nmo.sections.destroy',$s)}}">@csrf @method('DELETE')<button class="btn red" type="submit">Удалить раздел</button></form>
          <form data-ajax="1" method="post" action="{{route('admin.nmo.sections.copy',$s)}}">@csrf<select name="program_id" required>@foreach($programs as $p)<option value="{{$p->id}}" @selected($p->id===$program->id)>{{$p->title}}</option>@endforeach</select><button class="btn gray" type="submit">Копировать раздел</button></form>
        </div>
        <div class="upload-progress" hidden><i></i></div>
    </div>

    <div class="nmo-items" data-sort-kind="items">
    @forelse($s->contentItems as $x)
      @php($set=$x->settings?:[])
      <article class="nmo-item" draggable="true" data-sort-id="{{$x->id}}">
        <div class="nmo-item-row">
          <input class="item-check" type="checkbox" value="{{$x->id}}" aria-label="Выбрать">
          <span class="drag-handle small">⋮⋮</span>
          <span class="type-badge type-{{$x->legacy_type}}">{{$typeNames[$x->legacy_type]??$x->type}}</span>
          <div class="nmo-item-main"><strong>{{$x->title}}</strong><div class="nmo-meta">#{{$x->position}} @if($x->available_from)<span>с {{$x->available_from->format('d.m.Y H:i')}}</span>@endif @if($x->available_until)<span>до {{$x->available_until->format('d.m.Y H:i')}}</span>@endif @if($x->file_path)<span>📎 файл</span>@endif @if($x->external_url)<span>↗ ссылка</span>@endif</div></div>
          <div class="nmo-flags"><span class="mini-state {{$x->is_active?'good':'bad'}}">{{$x->is_active?'вкл':'выкл'}}</span>@if($x->is_required)<span class="mini-state">обяз.</span>@endif @if($x->flag)<span class="mini-state">гал</span>@endif</div>
          <button type="button" class="icon-btn" data-toggle-panel="itemEdit{{$x->id}}">✎</button>
        </div>
        @if($x->body)<div class="nmo-item-comment">{{Str::limit(strip_tags($x->body),180)}}</div>@endif
        <div id="itemEdit{{$x->id}}" class="nmo-collapse" hidden>
          <form data-ajax="1" method="post" enctype="multipart/form-data" action="{{route('admin.nmo.items.update',$x)}}" class="nmo-inline-editor">@csrf @method('PUT')
            <div class="nmo-grid-3"><div><label>Название</label><input name="title" value="{{$x->title}}" required></div><div><label>Порядок</label><input name="position" type="number" min="0" value="{{$x->position}}"></div><div><label>Повторов</label><input name="repeat_limit" type="number" min="1" value="{{$x->repeat_limit}}"></div></div>
            <label>Комментарий / текст</label><textarea name="body" rows="3">{{$x->body}}</textarea>
            <div class="nmo-grid-2"><div><label>URL</label><input name="external_url" value="{{$x->external_url}}"></div><div><label>Список вариантов / тем</label><textarea name="list_options" rows="3">{{implode("\n",$set['list_options']??[])}}</textarea></div></div>
            <div class="nmo-grid-3"><div><label>Доступ с</label><input type="datetime-local" name="available_from" value="{{$x->available_from?->format('Y-m-d\TH:i')}}"></div><div><label>Доступ до</label><input type="datetime-local" name="available_until" value="{{$x->available_until?->format('Y-m-d\TH:i')}}"></div><div><label>Цена</label><input type="number" step="0.01" min="0" name="price" value="{{$set['price']??''}}"></div></div>
            @if(in_array((int)$x->legacy_type,[15,22],true))<div class="nmo-grid-3"><div><label>Название сертификата</label><input name="certificate_name" value="{{$set['certificate_name']??''}}"></div><div><label>Часы</label><input type="number" min="0" name="certificate_hours" value="{{$set['certificate_hours']??''}}"></div><div><label>Текст сертификата</label><textarea name="certificate_text">{{$set['certificate_text']??''}}</textarea></div></div>@endif
            <div class="nmo-grid-2"><label class="drop-zone compact"><input type="file" name="file"><span>Заменить основной файл</span></label><label class="drop-zone compact"><input type="file" name="extra_file"><span>Заменить дополнительный файл</span></label></div>
            <div class="nmo-checks"><label><input type="checkbox" name="is_active" value="1" @checked($x->is_active)> Активен</label><label><input type="checkbox" name="is_required" value="1" @checked($x->is_required)> Обязательный</label><label><input type="checkbox" name="allow_duplicate" value="1" @checked($x->allow_duplicate)> Разрешить одинаковый выбор</label><label><input type="checkbox" name="flag" value="1" @checked($x->flag)> Флаг «гал»</label></div>
            <div class="upload-progress" hidden><i></i></div><div class="nmo-inline-actions"><button class="btn" type="submit">Сохранить</button></form>
              @if($x->file_path)<a class="btn gray" href="{{route('admin.nmo.items.download',$x)}}">Скачать файл</a>@endif
              @if($x->extra_file_path)<a class="btn gray" href="{{route('admin.nmo.items.download',[$x,'extra'])}}">Доп. файл</a>@endif
              <form data-ajax="1" data-confirm="Удалить этот учебный элемент?" method="post" action="{{route('admin.nmo.items.destroy',$x)}}">@csrf @method('DELETE')<button class="btn red" type="submit">Удалить</button></form>
            </div>
        </div>
      </article>
    @empty
      <div class="nmo-empty">В этом разделе пока нет учебных элементов.</div>
    @endforelse
    </div>

    <div class="nmo-section-footer">
      <button type="button" class="btn" data-open-add="{{$s->id}}">+ Добавить элемент</button>
      @if($s->contentItems->isNotEmpty())
      <form class="copy-selected-form" data-ajax="1" method="post" action="{{route('admin.nmo.items.copy-many')}}">@csrf
        <select name="learning_section_id" required><option value="">Копировать выбранные в...</option>@foreach($allSections as $target)<option value="{{$target->id}}">[{{$target->program?->title}}] {{$target->title}}</option>@endforeach</select>
        <button class="btn gray" type="submit">Копировать выбранные</button>
      </form>
      @endif
    </div>
  </section>
@empty
  <div class="nmo-empty big">У программы пока нет разделов. Нажмите «Новый раздел».</div>
@endforelse
</div>

<div id="addItemModal" class="nmo-modal" hidden>
  <div class="nmo-modal-backdrop" data-close-modal></div>
  <div class="nmo-modal-card">
    <div class="nmo-modal-head"><div><div class="nmo-eyebrow">Новый учебный элемент</div><h3 id="addItemSectionName">Добавление материала</h3></div><button type="button" class="nmo-x" data-close-modal>×</button></div>
    <form id="addItemForm" data-ajax="1" method="post" enctype="multipart/form-data" action="{{route('admin.nmo.items.store')}}">@csrf
      <input id="addItemSectionId" type="hidden" name="learning_section_id">
      <div class="nmo-grid-3"><div><label>Тип</label><select id="legacyTypeSelect" name="legacy_type" required>@foreach($legacyTypes as $id=>$t)<option value="{{$id}}">{{$t['name']}}</option>@endforeach</select></div><div><label>Название</label><input name="title" placeholder="Если пусто — возьмём имя файла"></div><div><label>Порядок</label><input type="number" name="position" min="0" value="0"></div></div>
      <div class="type-field" data-types="2,12"><label>URL</label><input name="external_url" type="url" placeholder="https://..."></div>
      <div class="type-field" data-types="1"><label>Документы</label><label class="drop-zone"><input type="file" name="files[]" multiple><span>Перетащите PDF/Word/другие документы<br><small>Можно выбрать несколько сразу</small></span></label></div>
      <div class="type-field" data-types="3,15,17,18,21,22"><label>HTML/ZIP пакет</label><label class="drop-zone"><input type="file" name="file" accept=".zip,.html,.htm"><span>Перетащите ZIP или HTML<br><small>ZIP будет безопасно распакован в приватное хранилище</small></span></label></div>
      <div class="type-field" data-types="7"><label>Файл</label><label class="drop-zone"><input type="file" name="file"><span>Перетащите файл</span></label><label>Идентификатор / значение поля</label><input name="source_value"></div>
      <div class="type-field" data-types="19"><label>CSV таблица</label><label class="drop-zone"><input type="file" name="file" accept=".csv,text/csv"><span>Перетащите CSV</span></label></div>
      <div class="type-field" data-types="21"><label>Файл с ответами</label><label class="drop-zone compact"><input type="file" name="extra_file" accept=".pdf"><span>PDF с ответами</span></label></div>
      <div class="type-field" data-types="15"><label>Дополнительное изображение / файл сертификатного теста</label><label class="drop-zone compact"><input type="file" name="extra_file"><span>Дополнительный файл</span></label></div>
      <div class="type-field nmo-grid-3" data-types="15,22"><div><label>Название сертификата</label><input name="certificate_name"></div><div><label>Количество часов</label><input type="number" min="0" name="certificate_hours"></div><div><label>Текст сертификата</label><textarea name="certificate_text" rows="2"></textarea></div></div>
      <div class="type-field" data-types="18"><label>Legacy-обработчик</label><label class="drop-zone compact"><input type="file" name="handler_file"><span>Можно сохранить старый обработчик для переноса логики</span></label><div class="safe-note">Файл хранится приватно и <b>не исполняется</b>. Логику старого PHP нужно переносить в Laravel-код.</div></div>
      <div class="type-field" data-types="4"><label>Список тем / вариантов</label><textarea name="list_options" rows="7" placeholder="Один вариант на строку"></textarea></div>
      <div class="type-field" data-types="6"><label>Анкета</label><select name="survey_id"><option value="">— без привязки —</option>@foreach($surveys as $survey)<option value="{{$survey->id}}">{{$survey->title}}</option>@endforeach</select></div>
      <div class="type-field" data-types="16"><label>Практика</label><select name="practice_cycle_id"><option value="">—</option>@foreach($practiceCycles as $cycle)<option value="{{$cycle->id}}">{{$cycle->title}}</option>@endforeach</select></div>
      <div class="type-field" data-types="11"><label>Стоимость</label><input type="number" name="price" min="0" step="0.01"></div>
      <div class="type-field nmo-grid-2" data-types="20"><div><label>От</label><input type="number" name="random_min"></div><div><label>До</label><input type="number" name="random_max"></div></div>
      <label>Комментарий / текст</label><textarea name="body" rows="3"></textarea>
      <div class="nmo-grid-3"><div><label>Доступ с</label><input type="datetime-local" name="available_from"></div><div><label>Доступ до</label><input type="datetime-local" name="available_until"></div><div><label>Количество повторов</label><input type="number" min="1" name="repeat_limit"></div></div>
      <div class="nmo-checks"><label><input type="checkbox" name="is_active" value="1" checked> Активен</label><label><input type="checkbox" name="is_required" value="1" checked> Обязательный</label><label><input type="checkbox" name="allow_duplicate" value="1"> Разрешить одинаковый выбор</label><label><input type="checkbox" name="flag" value="1"> Флаг «гал»</label></div>
      <div class="upload-progress" hidden><i></i></div><div class="nmo-modal-actions"><button class="btn gray" type="button" data-close-modal>Отмена</button><button class="btn" type="submit">Добавить элемент</button></div>
    </form>
  </div>
</div>
@else
<div class="nmo-empty big">Сначала создайте специальность / программу.</div>
@endif