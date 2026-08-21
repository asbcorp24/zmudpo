@extends('layouts.app')
@section('title','Конструктор обучения')
@section('content')
<div class="card"><div style="display:flex;justify-content:space-between;align-items:flex-end;gap:16px;flex-wrap:wrap"><div><h2 style="margin:0 0 6px">Конструктор НМО / ДПО</h2><div style="color:#667085">Современный аналог старого <b>add_nmo.php</b>. Большинство действий выполняются без перезагрузки страницы.</div></div><a class="btn gray" href="{{ route('admin.programs.index') }}">Специальности и программы</a></div></div>
<div id="ajaxNotice" style="display:none;margin-bottom:14px;padding:12px 14px;border-radius:10px;background:#ecfdf3;color:#166534"></div>
<div class="card"><label>Специальность / программа</label><select id="programSelector" name="program_id">@foreach($programs as $p)<option value="{{$p->id}}" @selected($program?->id==$p->id)>{{$p->title}}</option>@endforeach</select></div>
<div id="nmoWorkspace">@include('admin.legacy._nmo-workspace',['program'=>$program,'sections'=>$sections])</div>
<script>
(()=>{
 const workspace=document.getElementById('nmoWorkspace');
 const selector=document.getElementById('programSelector');
 const notice=document.getElementById('ajaxNotice');
 const headers={'X-Requested-With':'XMLHttpRequest','Accept':'application/json'};
 const show=(text,error=false)=>{notice.textContent=text;notice.style.display='block';notice.style.background=error?'#fef3f2':'#ecfdf3';notice.style.color=error?'#b42318':'#166534';clearTimeout(window.__nmoNoticeTimer);window.__nmoNoticeTimer=setTimeout(()=>notice.style.display='none',3500)};
 const programId=()=>selector?.value||'';
 async function refreshWorkspace(){
   const url=new URL(@json(route('admin.nmo.index')),window.location.origin);url.searchParams.set('program_id',programId());
   const r=await fetch(url,{headers:{'X-Requested-With':'XMLHttpRequest','Accept':'text/html'}});
   if(!r.ok)throw new Error('Не удалось обновить конструктор');
   workspace.innerHTML=await r.text();
   history.replaceState({},'',url.pathname+'?'+url.searchParams.toString());
 }
 selector?.addEventListener('change',async()=>{selector.disabled=true;try{await refreshWorkspace()}catch(e){show(e.message,true)}finally{selector.disabled=false}});
 document.addEventListener('submit',async e=>{
   const form=e.target.closest('form[data-ajax="1"]');if(!form)return;
   e.preventDefault();
   const button=form.querySelector('[type="submit"]');if(button)button.disabled=true;
   try{
     const r=await fetch(form.action,{method:(form.method||'POST').toUpperCase(),body:new FormData(form),headers});
     const data=await r.json().catch(()=>({message:'Ошибка сервера'}));
     if(!r.ok){const first=data.errors?Object.values(data.errors).flat()[0]:null;throw new Error(first||data.message||'Ошибка сохранения');}
     show(data.message||'Сохранено');
     await refreshWorkspace();
   }catch(err){show(err.message||'Ошибка',true)}finally{if(button)button.disabled=false}
 });
})();
</script>
@endsection