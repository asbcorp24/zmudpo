<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class ContentItemTicketAssignment extends Model
{
 protected $guarded=[];
 public function item(){return $this->belongsTo(ContentItem::class,'content_item_id');}
 public function user(){return $this->belongsTo(User::class);}
}
