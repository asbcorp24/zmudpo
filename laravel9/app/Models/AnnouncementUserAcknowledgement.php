<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class AnnouncementUserAcknowledgement extends Model
{
 protected $guarded=[];
 protected $casts=['acknowledged_at'=>'datetime'];
 public function announcement(){return $this->belongsTo(Announcement::class);}
 public function user(){return $this->belongsTo(User::class);}
}
