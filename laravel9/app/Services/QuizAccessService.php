<?php
namespace App\Services;

use App\Models\{QuizAssignment,QuizAttempt,QuizUserOverride,User};
use Illuminate\Auth\Access\AuthorizationException;

class QuizAccessService
{
    public function override(QuizAssignment $assignment, User $user): ?QuizUserOverride
    {
        return QuizUserOverride::where('quiz_assignment_id',$assignment->id)->where('user_id',$user->id)->first();
    }

    public function attemptLimit(QuizAssignment $assignment, User $user): ?int
    {
        $o=$this->override($assignment,$user);
        return $o && $o->attempt_limit !== null ? (int)$o->attempt_limit : ($assignment->attempt_limit !== null ? (int)$assignment->attempt_limit : null);
    }

    public function usedAttempts(QuizAssignment $assignment, User $user): int
    {
        return QuizAttempt::where('quiz_assignment_id',$assignment->id)->where('user_id',$user->id)->count();
    }

    public function assertAvailable(QuizAssignment $assignment, User $user): void
    {
        if (!$assignment->is_active) throw new AuthorizationException('Тест отключён администратором.');
        $o=$this->override($assignment,$user);
        $from=$o?->available_from ?? $assignment->available_from;
        $until=$o?->available_until ?? $assignment->available_until;
        if ($from && now()->lt($from)) throw new AuthorizationException('Тест ещё не открыт для прохождения.');
        if ($until && now()->gt($until)) throw new AuthorizationException('Срок прохождения теста завершён.');
        $limit=$this->attemptLimit($assignment,$user);
        if ($limit !== null && $this->usedAttempts($assignment,$user) >= $limit) throw new AuthorizationException('Лимит попыток исчерпан.');
    }

    public function status(QuizAssignment $assignment, User $user): array
    {
        $o=$this->override($assignment,$user);$limit=$this->attemptLimit($assignment,$user);$used=$this->usedAttempts($assignment,$user);
        $from=$o?->available_from ?? $assignment->available_from;$until=$o?->available_until ?? $assignment->available_until;
        $open=$assignment->is_active && (!$from||now()->gte($from)) && (!$until||now()->lte($until)) && ($limit===null||$used<$limit);
        return compact('o','limit','used','from','until','open');
    }
}