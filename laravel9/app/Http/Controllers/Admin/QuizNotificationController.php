<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\{QuizAssignment,User};
use App\Services\AuditService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class QuizNotificationController extends Controller
{
    public function send(Request $request, QuizAssignment $assignment, AuditService $audit)
    {
        abort_unless($assignment->is_active,422,'Сначала активируйте назначение теста.');
        $users=User::where('role','student')->where('is_active',true)->whereNotNull('email')
            ->whereHas('enrollments',fn($q)=>$q->where('program_id',$assignment->program_id)->where('status','active'))
            ->orderBy('id')->get(['id','full_name','email']);

        $already=DB::table('quiz_assignment_notifications')->where('quiz_assignment_id',$assignment->id)
            ->where('status','sent')->pluck('user_id')->all();
        $sent=$failed=$skipped=0;
        foreach($users as $user){
            if(in_array($user->id,$already,true)){$skipped++;continue;}
            $subject='Прохождение тестирования по ДПО';
            $url=route('quizzes.show',$assignment);
            $html='<h2>Здравствуйте, '.e($user->full_name).'</h2>'
                .'<p>Открыто тестирование «'.e($assignment->title).'».</p>'
                .'<p><a href="'.e($url).'">Перейти к тестированию</a></p>';
            try{
                Mail::html($html,function($message)use($user,$subject){$message->to($user->email,$user->full_name)->subject($subject);});
                DB::table('quiz_assignment_notifications')->updateOrInsert(
                    ['quiz_assignment_id'=>$assignment->id,'user_id'=>$user->id],
                    ['email'=>$user->email,'status'=>'sent','error'=>null,'sent_at'=>now(),'sent_by'=>auth()->id(),'created_at'=>now(),'updated_at'=>now()]
                );
                $sent++;
            }catch(\Throwable $e){
                DB::table('quiz_assignment_notifications')->updateOrInsert(
                    ['quiz_assignment_id'=>$assignment->id,'user_id'=>$user->id],
                    ['email'=>$user->email,'status'=>'failed','error'=>mb_substr($e->getMessage(),0,4000),'sent_at'=>null,'sent_by'=>auth()->id(),'created_at'=>now(),'updated_at'=>now()]
                );
                $failed++;
            }
        }
        $audit->write('quiz.assignment.notification',$assignment,[],['sent'=>$sent,'failed'=>$failed,'skipped'=>$skipped]);
        return back()->with('ok',"Уведомления: отправлено {$sent}, ранее отправлено {$skipped}, ошибок {$failed}.");
    }
}
