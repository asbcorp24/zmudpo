<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Program extends Model {protected $guarded=[]; protected $casts=['starts_at'=>'date','ends_at'=>'date','is_active'=>'boolean']; public function enrollments(){return $this->hasMany(Enrollment::class);} public function sections(){return $this->hasMany(LearningSection::class)->orderBy('position');} public function practiceAssignments(){return $this->hasMany(PracticeAssignment::class);} }