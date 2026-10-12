<!doctype html><html lang="ru"><head><meta charset="utf-8"><title>{{$document->title}}</title><style>
@page{size:A4 landscape;margin:12mm}body{font-family:"Times New Roman",serif;color:#111;margin:0}.sheet{min-height:180mm;border:3px double #333;padding:18mm 20mm;box-sizing:border-box;position:relative}.org{text-align:center;font-size:14px}.title{text-align:center;font-size:28px;font-weight:bold;text-transform:uppercase;margin:24px 0 8px}.subtitle{text-align:center;font-size:18px}.name{text-align:center;font-size:30px;font-weight:bold;margin:26px 0 18px}.program{text-align:center;font-size:19px;line-height:1.5}.meta{margin-top:32px;display:grid;grid-template-columns:1fr 1fr;gap:12px;font-size:16px}.footer{position:absolute;left:20mm;right:20mm;bottom:16mm;display:flex;justify-content:space-between}.no-print{position:fixed;right:18px;top:18px}@media print{.no-print{display:none}}
</style></head><body><button class="no-print" onclick="window.print()">Печать</button><div class="sheet">
<div class="org">ГАПОУ «Зеленодольский медицинский колледж»<br>Отделение дополнительного профессионального образования</div>
<div class="title">{{$document->type==='diploma'?'Диплом':($document->type==='certificate_nmo'?'Сертификат':'Удостоверение')}}</div>
<div class="subtitle">{{$document->title}}</div>
<div class="name">{{$document->user?->full_name}}</div>
<div class="program">успешно завершил(а) обучение по программе<br><strong>{{$document->program?->title}}</strong>@if(data_get($document->meta,'hours'))<br>в объёме {{data_get($document->meta,'hours')}} часов@endif</div>
@if(data_get($document->meta,'qualification'))<div class="program mt-2">Квалификация: <strong>{{data_get($document->meta,'qualification')}}</strong></div>@endif
<div class="meta"><div>Документ № <strong>{{$document->number??'—'}}</strong></div><div>Рег. № <strong>{{data_get($document->meta,'registration_number','—')}}</strong></div><div>Дата выдачи: <strong>{{$document->issued_at?->format('d.m.Y')}}</strong></div><div>Протокол: <strong>{{data_get($document->meta,'protocol','—')}}</strong></div></div>
<div class="footer"><span>Руководитель __________________</span><span>М.П.</span></div>
</div></body></html>