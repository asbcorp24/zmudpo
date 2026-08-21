<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class LearningSectionProgress extends Model {protected $table='learning_section_progress';protected $guarded=[];protected $casts=['completed'=>'boolean','legacy_date'=>'datetime','extra'=>'array'];public function user(){return $this->belongsTo(User::class);}public function section(){return $this->belongsTo(LearningSection::class,'learning_section_id');}public function program(){return $this->belongsTo(Program::class);}}
