<?php
namespace App\Http\Controllers\Curator;

use App\Http\Controllers\Controller;
use App\Models\{CuratorMessage,Enrollment};
use Illuminate\Http\Request;

class MessageController extends Controller
{
    public function index(Request $request)
    {
        $user=$request->user();
        $query=Enrollment::with(['user','program'])->whereIn('status',['active','completed']);
        if(!$user->isAdmin())$query->where('curator_id',$user->id);
        $enrollments=$query->get();
        $messages=CuratorMessage::whereIn('enrollment_id',$enrollments->pluck('id'))->orderBy('created_at')->get()->groupBy('enrollment_id');
        CuratorMessage::whereIn('enrollment_id',$enrollments->pluck('id'))->where('from_curator',false)->whereNull('read_at')->update(['read_at'=>now()]);
        return view('curator.messages',compact('enrollments','messages'));
    }

    public function send(Request $request, Enrollment $enrollment)
    {
        $user=$request->user();
        abort_unless($user->isAdmin() || $enrollment->curator_id===$user->id,403);
        $data=$request->validate(['message'=>['required','string','max:10000']]);
        CuratorMessage::create([
            'student_id'=>$enrollment->user_id,
            'curator_id'=>$enrollment->curator_id ?: $user->id,
            'enrollment_id'=>$enrollment->id,
            'message'=>$data['message'],
            'from_curator'=>true,
        ]);
        return back()->with('ok','Ответ отправлен слушателю.');
    }
}
