<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class QuizQuestion extends Model { protected $fillable=['quiz_id','legacy_id','question','type','position','points']; public function options(){return $this->hasMany(QuizOption::class)->orderBy('position');} }
