<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{LearningController,PracticeController,StudentProgramController,ProfileController,FinalWorkController,HistoryController,DocumentController};

Route::middleware('auth')->group(function(){
    Route::get('/programs/{enrollment}',[StudentProgramController::class,'show'])->name('programs.show');
    Route::post('/programs/{enrollment}/complete',[StudentProgramController::class,'complete'])->name('programs.complete');

    Route::get('/profile',[ProfileController::class,'show'])->name('profile.show');
    Route::put('/profile',[ProfileController::class,'update'])->name('profile.update');

    Route::get('/final-works',[FinalWorkController::class,'index'])->name('final-works.index');
    Route::post('/final-works/{enrollment}',[FinalWorkController::class,'submit'])->name('final-works.submit');

    Route::get('/history',[HistoryController::class,'index'])->name('history.index');
    Route::get('/documents/{document}/download',[DocumentController::class,'download'])->name('documents.download');

    Route::post('/learning/{section}/complete',[LearningController::class,'complete'])->name('learning.complete');
    Route::post('/practice/{assignment}/submit',[PracticeController::class,'submit'])->name('practice.submit');
});
