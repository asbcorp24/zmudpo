<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class LoginEvent extends Model {protected $guarded=[]; protected $casts=['logged_in_at'=>'datetime']; public function user(){return $this->belongsTo(User::class);} }