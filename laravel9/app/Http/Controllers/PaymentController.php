<?php
namespace App\Http\Controllers;

use App\Models\{ContentItem,ContentItemEntitlement,Enrollment,PaymentTransaction};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{
    public function start(Request $request,ContentItem $item)
    {
        $item->load('section');
        abort_unless((int)$item->legacy_type===11 && $item->is_active,404);
        abort_unless($item->section && Enrollment::where('user_id',$request->user()->id)->where('program_id',$item->section->program_id)->where('status','active')->exists(),403);

        $settings=$item->settings?:[];
        $amount=round((float)($settings['price']??0),2);
        $targets=array_values(array_unique(array_map('intval',(array)($settings['unlock_item_ids']??[]))));
        abort_if($amount<=0,422,'Для платного блока не указана стоимость.');
        abort_if(!$targets,422,'Для платного блока не выбраны материалы, которые нужно открыть.');

        $merchant=(string)config('services.robokassa.login');
        $password=(string)config('services.robokassa.password1');
        abort_if($merchant===''||$password==='',503,'Платёжный шлюз не настроен.');

        $tx=PaymentTransaction::create([
            'user_id'=>$request->user()->id,
            'content_item_id'=>$item->id,
            'amount'=>$amount,
            'status'=>'pending',
            'payload'=>['unlock_item_ids'=>$targets],
        ]);

        $sum=number_format($amount,2,'.','');
        $shp='Shp_item='.$item->id;
        $signature=md5($merchant.':'.$sum.':'.$tx->id.':'.$password.':'.$shp);
        $query=http_build_query([
            'MerchantLogin'=>$merchant,
            'OutSum'=>$sum,
            'InvId'=>$tx->id,
            'Description'=>'Оплата учебного блока: '.$item->title,
            'SignatureValue'=>$signature,
            'Shp_item'=>$item->id,
            'IsTest'=>config('services.robokassa.test')?1:0,
            'Culture'=>'ru',
            'Encoding'=>'utf-8',
        ]);
        return redirect()->away(rtrim((string)config('services.robokassa.url'),'/?').'?'.$query);
    }

    public function result(Request $request)
    {
        $sum=(string)$request->input('OutSum','');
        $inv=(int)$request->input('InvId',0);
        $itemId=(int)$request->input('Shp_item',0);
        $signature=strtoupper((string)$request->input('SignatureValue',''));
        $password=(string)config('services.robokassa.password2');
        abort_if($inv<=0||$itemId<=0||$sum===''||$password==='',400,'Bad request');

        $expected=strtoupper(md5($sum.':'.$inv.':'.$password.':Shp_item='.$itemId));
        abort_unless(hash_equals($expected,$signature),400,'bad sign');

        DB::transaction(function()use($request,$inv,$itemId,$sum){
            $tx=PaymentTransaction::lockForUpdate()->findOrFail($inv);
            abort_unless($tx->content_item_id===$itemId,400,'Wrong item');
            abort_unless(abs((float)$tx->amount-(float)$sum)<0.01,400,'Wrong amount');
            if($tx->status==='paid')return;

            $item=ContentItem::with('section')->findOrFail($itemId);
            $settings=$item->settings?:[];
            $targets=array_values(array_unique(array_map('intval',(array)($settings['unlock_item_ids']??data_get($tx->payload,'unlock_item_ids',[])))));
            $valid=ContentItem::whereIn('id',$targets)->whereHas('section',fn($q)=>$q->where('program_id',$item->section->program_id))->pluck('id');

            foreach($valid as $targetId){
                ContentItemEntitlement::updateOrCreate(
                    ['user_id'=>$tx->user_id,'content_item_id'=>$targetId],
                    ['payment_transaction_id'=>$tx->id,'source'=>'payment','granted_at'=>now()]
                );
            }
            $tx->update(['status'=>'paid','paid_at'=>now(),'payload'=>array_merge($tx->payload?:[],['callback'=>$request->except(['SignatureValue'])])]);
        });

        return response('OK'.$inv,200)->header('Content-Type','text/plain; charset=UTF-8');
    }

    public function success(Request $request)
    {
        $inv=(int)$request->input('InvId',0);
        $tx=$inv?PaymentTransaction::with('item.section')->find($inv):null;
        if($tx && $request->user() && $tx->user_id===$request->user()->id && $tx->item?->section){
            return redirect()->route('learning.show',$tx->item->section)->with('ok',$tx->status==='paid'?'Оплата подтверждена. Материалы открыты.':'Платёж принят и ожидает подтверждения.');
        }
        return redirect()->route('home');
    }
}
