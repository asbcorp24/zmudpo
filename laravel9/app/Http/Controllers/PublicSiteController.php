<?php
namespace App\Http\Controllers;

use App\Models\{News,Program,Testimonial};
use Illuminate\Http\Request;

class PublicSiteController extends Controller
{
 public function home()
 {
  $programCount=Program::where('is_active',true)->count();
  $featured=Program::where('is_active',true)->where(function($q){$q->where('is_featured_public',true)->orWhere('featured',true);})->orderByDesc('starts_at')->limit(6)->get();
  if($featured->isEmpty())$featured=Program::where('is_active',true)->orderByDesc('starts_at')->limit(6)->get();
  $news=News::where('is_active',true)->orderByDesc('published_at')->limit(5)->get();
  $testimonials=Testimonial::where('is_active',true)->orderByDesc('dated_at')->limit(6)->get();
  $categories=Program::where('is_active',true)->whereNotNull('category')->selectRaw('category,count(*) total')->groupBy('category')->orderByDesc('total')->limit(8)->get();
  return view('public.home',compact('featured','news','testimonials','categories','programCount'));
 }

 public function programs(Request $request)
 {
  $q=Program::where('is_active',true);
  if($request->filled('q')){$term=$request->string('q')->toString();$q->where(fn($x)=>$x->where('title','like','%'.$term.'%')->orWhere('category','like','%'.$term.'%')->orWhere('about','like','%'.$term.'%'));}
  if($request->filled('category'))$q->where('category',$request->string('category')->toString());
  if($request->filled('mode'))$q->where('mode',$request->string('mode')->toString());
  $programs=$q->orderByRaw('starts_at is null')->orderByDesc('starts_at')->paginate(18)->withQueryString();
  $categories=Program::where('is_active',true)->whereNotNull('category')->distinct()->orderBy('category')->pluck('category');
  return view('public.programs',compact('programs','categories'));
 }

 public function program(Program $program)
 {
  abort_unless($program->is_active,404);
  $program->load(['sections'=>fn($q)=>$q->where('is_active',true)]);
  $related=Program::where('is_active',true)->where('id','<>',$program->id)->when($program->category,fn($q)=>$q->where('category',$program->category))->limit(3)->get();
  return view('public.program',compact('program','related'));
 }
}
