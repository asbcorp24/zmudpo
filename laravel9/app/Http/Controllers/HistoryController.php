<?php
namespace App\Http\Controllers;

use App\Models\ArchiveRecord;
use Illuminate\Http\Request;

class HistoryController extends Controller
{
    public function index(Request $request)
    {
        $records=ArchiveRecord::with('program')->where('user_id',$request->user()->id)->latest('ended_at')->latest('id')->paginate(30);
        return view('history.index',compact('records'));
    }
}
