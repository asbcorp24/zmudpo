<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class LearningSection extends Model {
 protected $guarded=[];
 protected $casts=['is_active'=>'boolean','is_required'=>'boolean','available_from'=>'date','available_until'=>'date','settings'=>'array'];
 public function program(){return $this->belongsTo(Program::class);}
 public function parent(){return $this->belongsTo(self::class,'parent_id');}
 public function prerequisite(){return $this->belongsTo(self::class,'prerequisite_section_id');}
 public function contentItems(){return $this->hasMany(ContentItem::class)->orderBy('position');}
 public function instructors(){return $this->belongsToMany(Instructor::class,'learning_section_instructor')->withPivot('is_primary')->withTimestamps();}
}