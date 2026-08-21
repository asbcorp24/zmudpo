<?php
namespace App\Services;
use App\Models\{Enrollment,LearningSection,SectionProgress,User};
use Illuminate\Auth\Access\AuthorizationException;
class LearningAccessService {
 public function enrollment(User $user,int $programId): Enrollment { $e=Enrollment::where('user_id',$user->id)->where('program_id',$programId)->with('program')->firstOrFail(); if(!$e->program->is_active || !$e->isOpen()) throw new AuthorizationException('Доступ к программе сейчас закрыт.'); return $e; }
 public function canOpenSection(User $user,LearningSection $section): bool { try{$this->enrollment($user,$section->program_id);}catch(\Throwable $e){return false;} if(!$section->is_active) return false; if($section->available_from && now()->lt($section->available_from)) return false; if($section->available_until && now()->gt($section->available_until->endOfDay())) return false; if($section->prerequisite_section_id) return SectionProgress::where('user_id',$user->id)->where('learning_section_id',$section->prerequisite_section_id)->whereNotNull('completed_at')->exists(); return true; }
}