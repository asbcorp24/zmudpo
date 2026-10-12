<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentTransaction extends Model
{
    protected $guarded=[];
    protected $casts=['amount'=>'decimal:2','payload'=>'array','paid_at'=>'datetime'];
    public function user(){return $this->belongsTo(User::class);}
    public function item(){return $this->belongsTo(ContentItem::class,'content_item_id');}
}
