<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class ForumTopic extends Model { protected $fillable=['legacy_id','program_id','user_id','title','body','is_closed']; protected $casts=['is_closed'=>'boolean']; public function author(){return $this->belongsTo(User::class,'user_id');} }
