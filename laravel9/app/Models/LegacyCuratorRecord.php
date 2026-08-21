<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class LegacyCuratorRecord extends Model {protected $guarded=[];protected $casts=['occurred_at'=>'datetime','meta'=>'array'];public function user(){return $this->belongsTo(User::class);}}
