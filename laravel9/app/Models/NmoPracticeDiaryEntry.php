<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class NmoPracticeDiaryEntry extends Model
{
 protected $guarded=[];
 protected $casts=['work_date'=>'date'];
 public function item(){return $this->belongsTo(ContentItem::class,'content_item_id');}
 public function user(){return $this->belongsTo(User::class);}
}
