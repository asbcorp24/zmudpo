<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Group extends Model {protected $guarded=[]; public function enrollments(){return $this->hasMany(Enrollment::class);} public function users(){return $this->hasManyThrough(User::class,Enrollment::class,'group_id','id','id','user_id');} }