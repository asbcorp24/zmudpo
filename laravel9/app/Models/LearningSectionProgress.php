<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class LearningSectionProgress extends Model
{
 protected $table='learning_section_progress';
 protected $guarded=[];
 protected $casts=['completed'=>'boolean','legacy_date'=>'datetime','extra'=>'array'];
 public function user(){return $this->belongsTo(User::class);}
 public function section(){return $this->belongsTo(LearningSection::class,'learning_section_id');}
 public function program(){return $this->belongsTo(Program::class);}
 protected static function booted(): void
 {
  static::saved(function(self $legacy){
   if(!$legacy->user_id||!$legacy->learning_section_id)return;
   $progress=SectionProgress::firstOrNew(['user_id'=>$legacy->user_id,'learning_section_id'=>$legacy->learning_section_id]);
   $meta=(array)($progress->meta??[]);
   // Legacy data may restore a completion that would otherwise be lost during migration,
   // but a stale incomplete legacy row must never undo progress made in Laravel later.
   if($legacy->completed){
    $progress->progress_percent=100;
    if(!$progress->completed_at)$progress->completed_at=$legacy->legacy_date?:now();
   }elseif(!$progress->exists){
    $progress->progress_percent=0;
   }
   $meta['legacy_nmo']=true;$meta['legacy_nmo_progress_id']=$legacy->id;
   $progress->meta=$meta;$progress->save();
  });
 }
}
