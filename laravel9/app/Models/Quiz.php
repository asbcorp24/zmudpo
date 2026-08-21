<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Quiz extends Model { protected $fillable=['legacy_id','program_id','learning_section_id','title','description','pass_percent','attempt_limit','is_active']; protected $casts=['is_active'=>'boolean']; public function questions(){return $this->hasMany(QuizQuestion::class)->orderBy('position');} }
