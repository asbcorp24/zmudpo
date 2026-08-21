<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class EducationDocument extends Model { protected $fillable=['legacy_id','user_id','program_id','type','title','number','issued_at','file_path','meta']; protected $casts=['issued_at'=>'date','meta'=>'array']; }
