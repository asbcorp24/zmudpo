<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContentItemEntitlement extends Model
{
    protected $guarded=[];
    protected $casts=['granted_at'=>'datetime'];
    public function user(){return $this->belongsTo(User::class);}
    public function item(){return $this->belongsTo(ContentItem::class,'content_item_id');}
    public function transaction(){return $this->belongsTo(PaymentTransaction::class,'payment_transaction_id');}
}
