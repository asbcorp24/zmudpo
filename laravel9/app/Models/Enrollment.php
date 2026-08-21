<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Enrollment extends Model { protected $fillable=['user_id','program_id','group_id','legacy_user_id','status','started_at','completed_at']; protected $casts=['started_at'=>'date','completed_at'=>'date']; public function user(){return $this->belongsTo(User::class);} public function program(){return $this->belongsTo(Program::class);} public function group(){return $this->belongsTo(Group::class);} }
