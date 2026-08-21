<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Program extends Model {
 protected $guarded=[];
 protected $casts=['starts_at'=>'date','ends_at'=>'date','is_active'=>'boolean','featured'=>'boolean','registration_enabled'=>'boolean','is_featured_public'=>'boolean','settings'=>'array','price'=>'decimal:2'];
 public function enrollments(){return $this->hasMany(Enrollment::class);}
 public function sections(){return $this->hasMany(LearningSection::class)->orderBy('position');}
 public function practiceAssignments(){return $this->hasMany(PracticeAssignment::class);}
 public function type(){return $this->belongsTo(ProgramType::class,'program_type_id');}
}
