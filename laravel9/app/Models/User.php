<?php
namespace App\Models;
use Illuminate\Foundation\Auth\User as Authenticatable; use Illuminate\Notifications\Notifiable;
class User extends Authenticatable {use Notifiable; protected $guarded=[]; protected $hidden=['password','remember_token']; protected $casts=['email_verified_at'=>'datetime','is_active'=>'boolean','is_legal_entity'=>'boolean']; public function enrollments(){return $this->hasMany(Enrollment::class);} public function isAdmin():bool{return $this->role==='admin';} public function isCurator():bool{return in_array($this->role,['curator','admin'],true);} }