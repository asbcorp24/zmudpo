<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Submission extends Model { protected $fillable=['legacy_id','practice_assignment_id','user_id','file_path','comment','review_comment','score','status','submitted_at','reviewed_at','reviewed_by']; protected $casts=['submitted_at'=>'datetime','reviewed_at'=>'datetime']; public function user(){return $this->belongsTo(User::class);} public function assignment(){return $this->belongsTo(PracticeAssignment::class,'practice_assignment_id');} public function reviewer(){return $this->belongsTo(User::class,'reviewed_by');} }
