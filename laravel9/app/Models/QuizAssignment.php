<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class QuizAssignment extends Model
{
    protected $guarded=[];
    protected $casts=['is_active'=>'boolean','is_required'=>'boolean','available_from'=>'datetime','available_until'=>'datetime'];
    public function quiz(){return $this->belongsTo(Quiz::class);}
    public function program(){return $this->belongsTo(Program::class);}
    public function learningSection(){return $this->belongsTo(LearningSection::class);}
    public function attempts(){return $this->hasMany(QuizAttempt::class);}
    public function overrides(){return $this->hasMany(QuizUserOverride::class);}
}