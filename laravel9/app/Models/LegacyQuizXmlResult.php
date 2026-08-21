<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class LegacyQuizXmlResult extends Model {protected $guarded=[];protected $casts=['questions'=>'array','meta'=>'array'];public function user(){return $this->belongsTo(User::class);}public function section(){return $this->belongsTo(LearningSection::class,'learning_section_id');}public function attempt(){return $this->belongsTo(QuizAttempt::class,'quiz_attempt_id');}}
