<?php

use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Curator\DashboardController as CuratorDashboardController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\ForumController;
use App\Http\Controllers\LearningController;
use App\Http\Controllers\PracticeController;
use App\Http\Controllers\QuizController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn()=>redirect()->route('login'));
Route::middleware('guest')->group(function(){ Route::get('/login',[AuthController::class,'create'])->name('login'); Route::post('/login',[AuthController::class,'store'])->name('login.store'); });
Route::post('/logout',[AuthController::class,'destroy'])->middleware('auth')->name('logout');

Route::middleware('auth')->group(function(){
    Route::get('/dashboard',DashboardController::class)->name('dashboard');
    Route::get('/learning',[LearningController::class,'index'])->name('learning.index');
    Route::get('/learning/{section}',[LearningController::class,'show'])->name('learning.show');
    Route::post('/learning/{section}/complete',[LearningController::class,'complete'])->name('learning.complete');
    Route::get('/tests',[QuizController::class,'index'])->name('quizzes.index');
    Route::get('/tests/{quiz}',[QuizController::class,'show'])->name('quizzes.show');
    Route::post('/tests/{quiz}',[QuizController::class,'submit'])->name('quizzes.submit');
    Route::get('/practice',[PracticeController::class,'index'])->name('practice.index');
    Route::post('/practice/{assignment}/submit',[PracticeController::class,'submit'])->name('practice.submit');
    Route::get('/documents',[DocumentController::class,'index'])->name('documents.index');
    Route::get('/forum',[ForumController::class,'index'])->name('forum.index');
    Route::post('/forum',[ForumController::class,'store'])->name('forum.store');
});

Route::prefix('curator')->middleware(['auth','role:curator,admin'])->name('curator.')->group(function(){ Route::get('/',CuratorDashboardController::class)->name('dashboard'); });
Route::prefix('admin')->middleware(['auth','role:admin'])->name('admin.')->group(function(){ Route::get('/',AdminDashboardController::class)->name('dashboard'); });
