<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class LearningSection extends Model { protected $fillable=['legacy_id','program_id','parent_id','title','description','type','position','is_active']; protected $casts=['is_active'=>'boolean']; public function program(){return $this->belongsTo(Program::class);} public function contents(){return $this->hasMany(ContentItem::class)->orderBy('position');} }
