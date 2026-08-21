<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class QuizUserOverride extends Model
{
 protected $guarded=[];protected $casts=['available_from'=>'datetime','available_until'=>'datetime','is_active_override'=>'boolean'];
 public function assignment(){return $this->belongsTo(QuizAssignment::class,'quiz_assignment_id');}public function quiz(){return $this->belongsTo(Quiz::class);}public function user(){return $this->belongsTo(User::class);}public function changedBy(){return $this->belongsTo(User::class,'changed_by');}
}
