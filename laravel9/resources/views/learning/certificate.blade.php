@php($set=$item->settings?:[])
<!doctype html><html lang="ru"><head><meta charset="utf-8"><title>{{$set['certificate_name']??'Сертификат'}}</title><style>
@page{size:A4 landscape;margin:12mm}body{font-family:"Times New Roman",serif;margin:0;color:#1f2937}.sheet{min-height:180mm;border:4px double #64748b;padding:18mm 22mm;box-sizing:border-box;text-align:center;position:relative}.org{font-size:16px}.title{font-size:40px;font-weight:700;text-transform:uppercase;margin:24px 0 10px}.name{font-size:34px;font-weight:700;margin:30px 0 20px}.body{font-size:22px;line-height:1.55;max-width:900px;margin:auto}.meta{margin-top:30px;font-size:17px}.footer{position:absolute;bottom:18mm;left:22mm;right:22mm;display:flex;justify-content:space-between}.no-print{position:fixed;right:20px;top:20px}@media print{.no-print{display:none}}</style></head>
<body><button class="no-print" onclick="window.print()">Печать / сохранить PDF</button><div class="sheet">
<div class="org">ГАПОУ «Зеленодольский медицинский колледж»</div>
<div class="title">{{$set['certificate_name']??'Сертификат'}}</div>
<div class="name">{{auth()->user()->full_name}}</div>
<div class="body">{{$set['certificate_text']??'подтверждает успешное прохождение обучения и итогового тестирования'}}<br><strong>{{$item->section?->program?->title}}</strong>@if(!empty($set['certificate_hours']))<br>Объём: {{$set['certificate_hours']}} часов@endif</div>
<div class="meta">Дата: {{$attempt->finished_at?->format('d.m.Y')}} · Результат: {{number_format((float)$attempt->percent,1)}}%</div>
<div class="footer"><span>Руководитель __________________</span><span>М.П.</span></div>
</div></body></html>