<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class FinalWork extends Model {
 protected $guarded=[];
 protected $casts=['submitted_at'=>'datetime','reviewed_at'=>'datetime'];
 public function enrollment(){return $this->belongsTo(Enrollment::class);}
 public function user(){return $this->belongsTo(User::class);}
 public function reviewer(){return $this->belongsTo(User::class,'reviewed_by');}
 public function definition(){return $this->belongsTo(FinalWorkDefinition::class,'final_work_definition_id');}
 public function theme(){return $this->belongsTo(FinalWorkTheme::class,'final_work_theme_id');}
}
