<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class CuratorMessage extends Model
{
    protected $guarded=[];
    protected $casts=['from_curator'=>'boolean','read_at'=>'datetime'];
    public function student(){return $this->belongsTo(User::class,'student_id');}
    public function curator(){return $this->belongsTo(User::class,'curator_id');}
    public function enrollment(){return $this->belongsTo(Enrollment::class);}
}
