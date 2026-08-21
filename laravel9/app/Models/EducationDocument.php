<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class EducationDocument extends Model {protected $guarded=[]; protected $casts=['issued_at'=>'date']; public function user(){return $this->belongsTo(User::class);} public function program(){return $this->belongsTo(Program::class);} }