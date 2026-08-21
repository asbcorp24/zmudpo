<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Announcement extends Model {protected $guarded=[];protected $casts=['published_at'=>'datetime','expires_at'=>'datetime','is_active'=>'boolean'];public function author(){return $this->belongsTo(User::class,'author_id');}public function program(){return $this->belongsTo(Program::class);}public function group(){return $this->belongsTo(Group::class);}}
