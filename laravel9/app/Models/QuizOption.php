<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class QuizOption extends Model { protected $fillable=['quiz_question_id','legacy_id','text','is_correct','position']; protected $casts=['is_correct'=>'boolean']; }
