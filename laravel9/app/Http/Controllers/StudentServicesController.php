<?php
namespace App\Http\Controllers;

use App\Models\{CuratorMessage,Enrollment,InstructorProgram,InstructorSlot,ResourceLibrary,Survey,SurveyResponse};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class StudentServicesController extends Controller
{
    public function messages(Request $request)
    {
        $user=$request->user();
        $enrollments=$user->enrollments()->with(['program','curator'])->whereIn('status',['active','completed'])->get();
        $messages=CuratorMessage::with(['curator','enrollment.program'])->where('student_id',$user->id)->orderBy('created_at')->get()->groupBy('enrollment_id');
        CuratorMessage::where('student_id',$user->id)->where('from_curator',true)->whereNull('read_at')->update(['read_at'=>now()]);
        return view('student-services.messages',compact('enrollments','messages'));
    }

    public function sendMessage(Request $request, Enrollment $enrollment)
    {
        abort_unless($enrollment->user_id===$request->user()->id,403);
        abort_unless($enrollment->curator_id,422,'Для программы не назначен куратор.');
        $data=$request->validate(['message'=>['required','string','max:10000']]);
        CuratorMessage::create(['student_id'=>$request->user()->id,'curator_id'=>$enrollment->curator_id,'enrollment_id'=>$enrollment->id,'message'=>$data['message'],'from_curator'=>false]);
        return back()->with('ok','Сообщение отправлено куратору.');
    }

    public function schedule(Request $request)
    {
        $programIds=$request->user()->enrollments()->where('status','active')->pluck('program_id');
        $assignments=InstructorProgram::with(['instructor','program','slots'])->whereIn('program_id',$programIds)->get();
        $slotIds=$assignments->flatMap(fn($a)=>$a->slots->pluck('id'));
        $booked=DB::table('instructor_slot_user')->where('user_id',$request->user()->id)->pluck('slot_id')->all();
        $counts=$slotIds->isEmpty()?collect():DB::table('instructor_slot_user')->selectRaw('slot_id,count(*) as cnt')->whereIn('slot_id',$slotIds)->groupBy('slot_id')->pluck('cnt','slot_id');
        return view('student-services.schedule',compact('assignments','booked','counts'));
    }

    public function bookSlot(Request $request, InstructorSlot $slot)
    {
        $slot->load('instructorProgram');
        $assignment=$slot->instructorProgram;
        abort_unless($assignment && $request->user()->enrollments()->where('program_id',$assignment->program_id)->where('status','active')->exists(),403);
        abort_if($slot->date && now()->gt($slot->date->endOfDay()),422,'Дата занятия уже прошла.');
        DB::transaction(function() use($request,$slot){
            $exists=DB::table('instructor_slot_user')->where('slot_id',$slot->id)->where('user_id',$request->user()->id)->exists();
            if($exists)return;
            $count=DB::table('instructor_slot_user')->where('slot_id',$slot->id)->lockForUpdate()->count();
            abort_if($slot->capacity && $count >= $slot->capacity,422,'Свободных мест нет.');
            DB::table('instructor_slot_user')->insert(['slot_id'=>$slot->id,'user_id'=>$request->user()->id,'created_at'=>now(),'updated_at'=>now()]);
        });
        return back()->with('ok','Запись на занятие сохранена.');
    }

    public function cancelSlot(Request $request, InstructorSlot $slot)
    {
        DB::table('instructor_slot_user')->where('slot_id',$slot->id)->where('user_id',$request->user()->id)->delete();
        return back()->with('ok','Запись отменена.');
    }

    public function surveys(Request $request)
    {
        $surveys=Survey::with(['fields'=>fn($q)=>$q->orderBy('position')])->where('is_active',true)->get();
        $responses=SurveyResponse::where('user_id',$request->user()->id)->get()->keyBy('survey_field_id');
        return view('student-services.surveys',compact('surveys','responses'));
    }

    public function submitSurvey(Request $request, Survey $survey)
    {
        abort_unless($survey->is_active,403);
        $fields=$survey->fields()->orderBy('position')->get();
        foreach($fields as $field){
            $value=$request->input('field_'.$field->id);
            if(is_array($value))$value=json_encode(array_values($value),JSON_UNESCAPED_UNICODE);
            SurveyResponse::updateOrCreate(['survey_field_id'=>$field->id,'user_id'=>$request->user()->id],['value'=>$value]);
        }
        return back()->with('ok','Ответы сохранены.');
    }

    public function resources(Request $request)
    {
        $programIds=$request->user()->enrollments()->whereIn('status',['active','completed'])->pluck('program_id');
        $resources=ResourceLibrary::where('is_active',true)->where(function($q)use($programIds){
            $q->whereExists(function($s)use($programIds){$s->selectRaw('1')->from('program_resource')->whereColumn('program_resource.resource_id','resource_library.id')->whereIn('program_resource.program_id',$programIds);})
              ->orWhereExists(function($s){$s->selectRaw('1')->from('program_resource')->whereColumn('program_resource.resource_id','resource_library.id')->whereNull('program_resource.program_id');});
        })->orderByDesc('dated_at')->orderBy('title')->get();
        return view('student-services.resources',compact('resources'));
    }

    public function downloadResource(Request $request, ResourceLibrary $resource)
    {
        abort_unless($resource->is_active && $resource->file_path && Storage::exists($resource->file_path),404);
        $programIds=$request->user()->enrollments()->whereIn('status',['active','completed'])->pluck('program_id');
        $allowed=DB::table('program_resource')->where('resource_id',$resource->id)->where(function($q)use($programIds){$q->whereNull('program_id')->orWhereIn('program_id',$programIds);})->exists();
        abort_unless($allowed,403);
        return Storage::download($resource->file_path);
    }
}
