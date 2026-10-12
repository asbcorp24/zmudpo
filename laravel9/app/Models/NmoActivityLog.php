<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class NmoActivityLog extends Model
{
 protected $guarded=[];
 protected $casts=['meta'=>'array'];
 public function user(){return $this->belongsTo(User::class);}
 public function program(){return $this->belongsTo(Program::class);}
 public function section(){return $this->belongsTo(LearningSection::class,'learning_section_id');}
 public function item(){return $this->belongsTo(ContentItem::class,'content_item_id');}
}
