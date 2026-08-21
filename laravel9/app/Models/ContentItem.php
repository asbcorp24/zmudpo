<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class ContentItem extends Model { protected $fillable=['legacy_id','learning_section_id','title','type','body','file_path','external_url','position','is_required']; protected $casts=['is_required'=>'boolean']; }
