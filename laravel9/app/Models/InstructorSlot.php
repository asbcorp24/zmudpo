<?php
namespace App\Models;
class InstructorSlot extends LegacyAdminModel
{
    protected $casts=['date'=>'date'];
    public function instructorProgram(){return $this->belongsTo(InstructorProgram::class);}
    public function users(){return $this->belongsToMany(User::class,'instructor_slot_user','slot_id','user_id')->withTimestamps();}
}
