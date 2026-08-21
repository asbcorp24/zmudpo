<!doctype html>
<html lang="ru">
<head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>Вход в обучение · ZMUDPO</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('css/public-site.css') }}">
<style>
.login-shell{min-height:100vh;background:radial-gradient(circle at 10% 10%,#dfeaff 0,transparent 32%),linear-gradient(135deg,#f7fbff,#edf2ff);padding:34px 0 60px}.login-brand{display:flex;align-items:center;gap:12px;color:#172033;text-decoration:none}.login-brand .brand-badge{flex:0 0 46px}.login-grid{display:grid;grid-template-columns:minmax(0,1.15fr) minmax(360px,.85fr);gap:30px;margin-top:34px}.program-picker,.signin-panel{background:#fff;border:1px solid #e5eaf2;border-radius:28px;box-shadow:0 22px 60px rgba(31,50,90,.10)}.program-picker{padding:30px}.signin-panel{padding:34px;align-self:start;position:sticky;top:24px}.program-tabs{display:flex;gap:8px;flex-wrap:wrap;margin:22px 0}.program-tab{border:0;border-radius:999px;padding:8px 15px;background:#eef3ff;color:#2458d3;font-weight:700}.program-list{display:grid;gap:10px;max-height:520px;overflow:auto;padding-right:5px}.program-choice{display:flex;gap:14px;align-items:center;padding:14px;border:1px solid #e8edf5;border-radius:18px;text-decoration:none;color:#172033;transition:.2s}.program-choice:hover,.program-choice.active{border-color:#9eb8ff;background:#f4f7ff;transform:translateY(-1px)}.program-thumb{width:58px;height:58px;border-radius:14px;background:#e9efff;overflow:hidden;display:grid;place-items:center;color:#2458d3;font-weight:800;flex:0 0 58px}.program-thumb img{width:100%;height:100%;object-fit:cover}.program-choice small{display:block;color:#788399;margin-top:3px}.selected-course{padding:15px 16px;border-radius:18px;background:#f3f6ff;border:1px solid #dce6ff;margin-bottom:22px}.selected-course strong{display:block}.quick-login{font-size:13px;color:#7a8497}.form-control{border-radius:14px;padding:.86rem 1rem}.btn-login{border-radius:14px;padding:.9rem 1rem;font-weight:700}.back-link{text-decoration:none;color:#5d6880}.empty-program{padding:18px;border:1px dashed #ccd5e4;border-radius:16px;color:#778197}@media(max-width:991px){.login-grid{grid-template-columns:1fr}.signin-panel{position:static}.program-list{max-height:none}}
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
    <h1 class="mt-3 mb-2">Выберите программу</h1>
    <p class="text-secondary mb-0">Как и в прежней системе: сначала выбирается специальность или цикл, затем выполняется вход в свою учебную группу.</p>
    <div class="program-tabs"><button class="program-tab" type="button" data-mode="all">Все</button><button class="program-tab" type="button" data-mode="dpo">ДПО</button><button class="program-tab" type="button" data-mode="nmo">НМО</button></div>
    <div class="program-list" id="programList">
     @forelse($programs as $program)
      <a class="program-choice {{ optional($selectedProgram)->id===$program->id?'active':'' }}" data-mode="{{$program->mode}}" href="{{ route('login',['program'=>$program->id]) }}">
       <span class="program-thumb">@if($program->image)<img src="{{ asset('timg/'.$program->image) }}" alt="">@else{{ strtoupper(substr($program->mode,0,1)) }}@endif</span>
       <span class="flex-grow-1"><strong>{{$program->title}}</strong><small>{{strtoupper($program->mode)}}@if($program->hours) · {{$program->hours}} ч.@endif @if($program->starts_at) · {{$program->starts_at->format('d.m.Y')}}@endif</small></span>
       <span>→</span>
      </a>
     @empty
      <div class="empty-program">Активные программы появятся после импорта или создания в админке.</div>
     @endforelse
    </div>
   </section>

   <aside class="signin-panel">
    @if($selectedProgram)
     <div class="selected-course"><small class="text-secondary">Вы входите в программу</small><strong>{{$selectedProgram->title}}</strong><span class="badge text-bg-primary mt-2">{{strtoupper($selectedProgram->mode)}}</span></div>
    @else
     <div class="selected-course"><small class="text-secondary">Быстрый вход</small><strong>Личный кабинет</strong><div class="quick-login mt-1">Если программа не выбрана, после входа откроется общий кабинет.</div></div>
    @endif
    <h2 class="mb-2">Введите данные</h2>
    <p class="text-secondary">Используйте ваш номер пользователя/логин и пароль.</p>
    @if($errors->any())<div class="alert alert-danger">{{$errors->first()}}</div>@endif
    <form method="post" action="{{ route('login.perform') }}">@csrf
     @if($selectedProgram)<input type="hidden" name="program_id" value="{{$selectedProgram->id}}">@endif
     <div class="mb-3"><label class="form-label">Логин</label><input name="login" value="{{ old('login') }}" class="form-control form-control-lg" autocomplete="username" autofocus required></div>
     <div class="mb-3"><label class="form-label">Пароль</label><input type="password" name="password" class="form-control form-control-lg" autocomplete="current-password" required></div>
     <div class="d-flex justify-content-between align-items-center gap-3 mb-4"><div class="form-check"><input class="form-check-input" type="checkbox" name="remember" value="1" id="remember"><label class="form-check-label" for="remember">Запомнить меня</label></div>@if($selectedProgram)<a href="{{route('login')}}" class="small">Войти без выбора программы</a>@endif</div>
     <button class="btn btn-primary btn-lg w-100 btn-login">{{$selectedProgram?'Войти в группу':'Войти в кабинет'}}</button>
    </form>
    <div class="quick-login mt-4">После миграции старый номер пользователя используется как логин. Список ФИО публично не показывается — это сохранено только как внутренняя привязка учётной записи.</div>
   </aside>
  </div>
 </div>
</div>
<script>
document.querySelectorAll('.program-tab').forEach(btn=>btn.addEventListener('click',()=>{const mode=btn.dataset.mode;document.querySelectorAll('.program-choice').forEach(x=>x.style.display=(mode==='all'||x.dataset.mode===mode)?'flex':'none');}));
</script>
</body></html>
