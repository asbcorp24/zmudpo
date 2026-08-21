<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Enrollment extends Model {
 protected $guarded=[]; protected $casts=['started_at'=>'date','ends_at'=>'date','completed_at'=>'datetime','blocked_at'=>'datetime','progress_percent'=>'decimal:2'];
 public function user(){return $this->belongsTo(User::class);} public function program(){return $this->belongsTo(Program::class);} public function group(){return $this->belongsTo(Group::class);} public function curator(){return $this->belongsTo(User::class,'curator_id');}
 public function finalWorks(){return $this->hasMany(FinalWork::class);} public function isOpen(): bool {return $this->status==='active' && !$this->blocked_at && (!$this->started_at || $this->started_at->isPast()) && (!$this->ends_at || $this->ends_at->endOfDay()->isFuture());}
}