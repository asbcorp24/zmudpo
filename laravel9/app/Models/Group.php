<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Group extends Model { protected $fillable=['legacy_id','name']; public function enrollments(){return $this->hasMany(Enrollment::class);} }
