<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class QuizAttempt extends Model {protected $guarded=[]; protected $casts=['answers'=>'array','passed'=>'boolean','started_at'=>'datetime','finished_at'=>'datetime']; public function quiz(){return $this->belongsTo(Quiz::class);} public function user(){return $this->belongsTo(User::class);} }