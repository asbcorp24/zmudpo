<!doctype html>
<html lang="ru">
<head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>Вход в обучение · ZMUDPO</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('css/public-site.css') }}">
<style>
.login-shell{min-height:100vh;background:radial-gradient(circle at 10% 10%,#dfeaff 0,transparent 32%),linear-gradient(135deg,#f7fbff,#edf2ff);padding:34px 0 60px}.login-brand{display:flex;align-items:center;gap:12px;color:#172033;text-decoration:none}.login-brand .brand-badge{flex:0 0 46px}.login-grid{display:grid;grid-template-columns:minmax(0,1.15fr) minmax(360px,.85fr);gap:30px;margin-top:34px}.program-picker,.signin-panel{background:#fff;border:1px solid #e5eaf2;border-radius:28px;box-shadow:0 22px 60px rgba(31,50,90,.10)}.program-picker{padding:30px}.signin-panel{padding:34px;align-self:start;position:sticky;top:24px}.program-tabs{display:flex;gap:8px;flex-wrap:wrap;margin:22px 0}.program-tab{border:1px solid #dce6ff;border-radius:999px;padding:9px 15px;background:#fff;color:#2458d3;font-weight:700}.program-tab:hover,.program-tab.active{background:#2458d3;color:#fff;border-color:#2458d3}.program-list{display:grid;gap:10px;max-height:520px;overflow:auto;padding-right:5px}.program-choice{display:flex;gap:14px;align-items:center;padding:14px;border:1px solid #e8edf5;border-radius:18px;text-decoration:none;color:#172033;transition:.2s}.program-choice:hover,.program-choice.active{border-color:#9eb8ff;background:#f4f7ff;transform:translateY(-1px)}.program-thumb{width:58px;height:58px;border-radius:14px;background:#e9efff;overflow:hidden;display:grid;place-items:center;color:#2458d3;font-weight:800;flex:0 0 58px}.program-thumb img{width:100%;height:100%;object-fit:cover}.program-choice small{display:block;color:#788399;margin-top:3px}.selected-course{padding:15px 16px;border-radius:18px;background:#f3f6ff;border:1px solid #dce6ff;margin-bottom:22px}.selected-course strong{display:block}.quick-login{font-size:13px;color:#7a8497}.form-control,.form-select{border-radius:14px;padding:.86rem 1rem}.btn-login{border-radius:14px;padding:.9rem 1rem;font-weight:700}.back-link{text-decoration:none;color:#5d6880}.empty-program{padding:18px;border:1px dashed #ccd5e4;border-radius:16px;color:#778197}.entity-switch{display:flex;align-items:center;justify-content:space-between;gap:14px;padding:12px 14px;border:1px solid #e4eaf4;border-radius:16px;background:#fafbfe}.entity-switch .form-check{margin:0}.student-count{font-size:12px;color:#7a8497}@media(max-width:991px){.login-grid{grid-template-columns:1fr}.signin-panel{position:static}.program-list{max-height:none}}
</style>
</head>
<body>
<div class="login-shell">
 <div class="container">
  <div class="d-flex justify-content-between align-items-center gap-3 flex-wrap">
   <a class="login-brand" href="{{ route('home') }}"><span class="brand-badge">Z</span><div><strong>ZMUDPO</strong><small>Дополнительное профессиональное образование</small></div></a>
   <a class="back-link" href="{{ route('home') }}">← На главную</a>
  </div>
  <div class="login-grid">
   <section class="program-picker">
    <span class="hero-kicker">Вход в обучение</span>
    <h1 class="mt-3 mb-2">Выберите специальность</h1>
    <p class="text-secondary mb-0">Выберите направление обучения, затем специальность и свою фамилию в группе.</p>
    <div class="program-tabs">
     <button class="program-tab {{!$selectedProgram?'active':''}}" type="button" data-type="all">Все направления</button>
     @foreach($programTypes as $type)
      <button class="program-tab {{optional($selectedProgram)->program_type_id===$type->id?'active':''}}" type="button" data-type="{{$type->id}}">{{$type->name}}</button>
     @endforeach
    </div>
    <div class="program-list" id="programList">
     @forelse($programs as $program)
      <a class="program-choice {{ optional($selectedProgram)->id===$program->id?'active':'' }}" data-type="{{$program->program_type_id?:'none'}}" href="{{ route('login',['program'=>$program->id]) }}">
       <span class="program-thumb">@if($program->image)<img src="{{ asset('timg/'.$program->image) }}" alt="">@else{{strtoupper(substr($program->mode,0,1))}}@endif</span>
       <span class="flex-grow-1"><strong>{{$program->title}}</strong><small>@if($program->type){{$program->type->name}} · @endif{{strtoupper($program->mode)}}@if($program->hours) · {{$program->hours}} ч.@endif @if($program->starts_at) · {{$program->starts_at->format('d.m.Y')}}@endif</small></span>
       <span>→</span>
      </a>
     @empty
      <div class="empty-program">Активные программы появятся после импорта или создания в админке.</div>
     @endforelse
    </div>
   </section>

   <aside class="signin-panel">
    @if($selectedProgram)
     <div class="selected-course"><small class="text-secondary">Вы входите в программу</small><strong>{{$selectedProgram->title}}</strong><div class="d-flex gap-2 flex-wrap mt-2">@if($selectedProgram->type)<span class="badge text-bg-light border">{{$selectedProgram->type->name}}</span>@endif<span class="badge text-bg-primary">{{strtoupper($selectedProgram->mode)}}</span></div></div>
    @else
     <div class="selected-course"><small class="text-secondary">Быстрый вход</small><strong>Личный кабинет</strong><div class="quick-login mt-1">Можно войти без выбора программы по своему логину.</div></div>
    @endif
    <h2 class="mb-2">Введите данные</h2>
    @if($errors->any())<div class="alert alert-danger">{{$errors->first()}}</div>@endif
    <form method="post" action="{{ route('login.perform') }}">@csrf
     @if($selectedProgram)
      <input type="hidden" name="program_id" value="{{$selectedProgram->id}}">
      <div class="entity-switch mb-3">
       <div><strong>Тип слушателя</strong><div class="student-count" id="studentCount"></div></div>
       <div class="form-check form-switch"><input class="form-check-input" type="checkbox" role="switch" id="legalToggle"><label class="form-check-label" for="legalToggle">Юридическое лицо</label></div>
      </div>
      <div class="mb-3">
       <label class="form-label">Выберите ФИО</label>
       <select name="login" id="studentSelect" class="form-select form-select-lg" required><option value="">— выберите слушателя —</option></select>
      </div>
      <script type="application/json" id="individualUsers">@json($individuals->map(fn($u)=>['login'=>$u->login,'name'=>$u->full_name])->values())</script>
      <script type="application/json" id="legalUsers">@json($legalEntities->map(fn($u)=>['login'=>$u->login,'name'=>$u->full_name])->values())</script>
     @else
      <div class="mb-3"><label class="form-label">Логин</label><input name="login" value="{{ old('login') }}" class="form-control form-control-lg" autocomplete="username" autofocus required></div>
     @endif
     <div class="mb-3"><label class="form-label">Пароль</label><input type="password" name="password" class="form-control form-control-lg" autocomplete="current-password" required></div>
     <div class="d-flex justify-content-between align-items-center gap-3 mb-4"><div class="form-check"><input class="form-check-input" type="checkbox" name="remember" value="1" id="remember"><label class="form-check-label" for="remember">Запомнить меня</label></div>@if($selectedProgram)<a href="{{route('login')}}" class="small">Войти без выбора программы</a>@endif</div>
     <button class="btn btn-primary btn-lg w-100 btn-login">{{$selectedProgram?'Войти в группу':'Войти в кабинет'}}</button>
    </form>
   </aside>
  </div>
 </div>
</div>
<script>
const typeButtons=document.querySelectorAll('.program-tab');
const choices=document.querySelectorAll('.program-choice');
typeButtons.forEach(btn=>btn.addEventListener('click',()=>{const type=btn.dataset.type;typeButtons.forEach(x=>x.classList.remove('active'));btn.classList.add('active');choices.forEach(x=>x.style.display=(type==='all'||x.dataset.type===type)?'flex':'none');}));
@if($selectedProgram && $selectedProgram->program_type_id)
document.querySelector('.program-tab[data-type="{{$selectedProgram->program_type_id}}"]')?.click();
@endif
const select=document.getElementById('studentSelect');
if(select){
 const individuals=JSON.parse(document.getElementById('individualUsers').textContent||'[]');
 const legal=JSON.parse(document.getElementById('legalUsers').textContent||'[]');
 const toggle=document.getElementById('legalToggle');
 const count=document.getElementById('studentCount');
 const oldLogin=@json(old('login'));
 const render=()=>{const list=toggle.checked?legal:individuals;select.innerHTML='<option value="">— выберите слушателя —</option>';list.forEach(u=>{const o=document.createElement('option');o.value=u.login;o.textContent=u.name;if(String(oldLogin)===String(u.login))o.selected=true;select.appendChild(o);});count.textContent='В списке: '+list.length;};
 toggle.addEventListener('change',render);render();
}
</script>
</body></html>
