<?php
namespace App\Services;
use App\Models\AuditLog;
class AuditService {public function write(string $action,$entity=null,array $before=[],array $after=[]): void {AuditLog::create(['user_id'=>auth()->id(),'action'=>$action,'entity_type'=>$entity?get_class($entity):null,'entity_id'=>$entity?->id,'before'=>$before?:null,'after'=>$after?:null,'ip'=>request()->ip()]);}}
