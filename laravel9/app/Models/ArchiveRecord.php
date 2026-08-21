<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class ArchiveRecord extends Model {protected $guarded=[]; protected $casts=['data'=>'array','started_at'=>'date','ended_at'=>'date']; public function user(){return $this->belongsTo(User::class);} public function program(){return $this->belongsTo(Program::class);} }