<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class ContentItem extends Model {
 protected $guarded=[];
 protected $casts=[
  'is_required'=>'boolean','is_active'=>'boolean','allow_duplicate'=>'boolean','flag'=>'boolean',
  'settings'=>'array','available_from'=>'datetime','available_until'=>'datetime'
 ];
 public function section(){return $this->belongsTo(LearningSection::class,'learning_section_id');}
}