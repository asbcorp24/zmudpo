<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class QuizAttempt extends Model
{
    protected $guarded=[]; protected $casts=['answers'=>'array','passed'=>'boolean','started_at'=>'datetime','finished_at'=>'datetime','adjusted_at'=>'datetime'];
    public function quiz(){return $this->belongsTo(Quiz::class);}
    public function assignment(){return $this->belongsTo(QuizAssignment::class,'quiz_assignment_id');}
    public function user(){return $this->belongsTo(User::class);}
    public function adjustedBy(){return $this->belongsTo(User::class,'adjusted_by');}
}