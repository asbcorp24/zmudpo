<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class QuizAttempt extends Model { protected $fillable=['quiz_id','user_id','legacy_id','score','percent','passed','answers','started_at','finished_at','ip']; protected $casts=['passed'=>'boolean','answers'=>'array','started_at'=>'datetime','finished_at'=>'datetime']; }
