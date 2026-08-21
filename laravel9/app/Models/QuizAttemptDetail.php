<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class QuizAttemptDetail extends Model {protected $guarded=[];protected $casts=['questions'=>'array','extra'=>'array'];public function attempt(){return $this->belongsTo(QuizAttempt::class,'quiz_attempt_id');}}
