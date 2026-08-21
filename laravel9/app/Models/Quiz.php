<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Quiz extends Model {protected $guarded=[]; protected $casts=['is_active'=>'boolean','is_required'=>'boolean']; public function program(){return $this->belongsTo(Program::class);} public function learningSection(){return $this->belongsTo(LearningSection::class);} public function questions(){return $this->hasMany(QuizQuestion::class)->orderBy('position');} public function attempts(){return $this->hasMany(QuizAttempt::class);} }