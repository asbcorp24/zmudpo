<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Announcement extends Model { protected $fillable=['legacy_id','program_id','author_id','title','body','published_at','is_active']; protected $casts=['published_at'=>'datetime','is_active'=>'boolean']; }
