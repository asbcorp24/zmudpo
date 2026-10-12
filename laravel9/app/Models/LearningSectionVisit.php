<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class LearningSectionVisit extends Model
{
 protected $guarded=[];
 protected $casts=['visited_at'=>'datetime'];
 public function section(){return $this->belongsTo(LearningSection::class,'learning_section_id');}
 public function user(){return $this->belongsTo(User::class);}
}
