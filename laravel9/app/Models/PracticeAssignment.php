<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class PracticeAssignment extends Model { protected $fillable=['legacy_id','program_id','learning_section_id','title','description','starts_at','ends_at','is_active']; protected $casts=['starts_at'=>'date','ends_at'=>'date','is_active'=>'boolean']; }
