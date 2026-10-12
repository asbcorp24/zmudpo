<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class AttendanceRecord extends Model
{
 protected $guarded=[];
 protected $casts=['attendance_date'=>'date'];
 public function enrollment(){return $this->belongsTo(Enrollment::class);}
 public function marker(){return $this->belongsTo(User::class,'marked_by');}
}
