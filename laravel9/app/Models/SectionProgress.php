<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class SectionProgress extends Model { protected $fillable=['user_id','learning_section_id','progress_percent','completed_at','meta']; protected $casts=['completed_at'=>'datetime','meta'=>'array']; }
