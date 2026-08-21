<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Program extends Model { protected $fillable=['legacy_id','title','starts_at','ends_at','image','is_active','mode','hours']; protected $casts=['starts_at'=>'date','ends_at'=>'date','is_active'=>'boolean']; public function sections(){return $this->hasMany(LearningSection::class)->orderBy('position');} public function enrollments(){return $this->hasMany(Enrollment::class);} }
