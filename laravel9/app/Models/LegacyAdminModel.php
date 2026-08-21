<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
abstract class LegacyAdminModel extends Model {protected $guarded=[];protected $casts=['settings'=>'array','options'=>'array'];}
