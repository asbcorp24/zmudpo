<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
class User extends Authenticatable {
 use HasFactory,Notifiable;
 protected $fillable=['legacy_id','login','full_name','email','password','role','is_active','is_legal_entity'];
 protected $hidden=['password','remember_token'];
 protected $casts=['is_active'=>'boolean','is_legal_entity'=>'boolean','email_verified_at'=>'datetime'];
 public function enrollments(){ return $this->hasMany(Enrollment::class); }
 public function programs(){ return $this->belongsToMany(Program::class,'enrollments')->withPivot(['group_id','status','started_at','completed_at'])->withTimestamps(); }
 public function isAdmin(): bool { return $this->role==='admin'; }
 public function isCurator(): bool { return in_array($this->role,['curator','admin'],true); }
}
