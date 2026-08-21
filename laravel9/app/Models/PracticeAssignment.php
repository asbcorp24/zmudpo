<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class PracticeAssignment extends Model {protected $guarded=[]; protected $casts=['starts_at'=>'date','ends_at'=>'date','is_active'=>'boolean']; public function program(){return $this->belongsTo(Program::class);} public function learningSection(){return $this->belongsTo(LearningSection::class);} public function submissions(){return $this->hasMany(Submission::class);} }