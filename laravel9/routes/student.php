<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{LearningController,PracticeController,StudentProgramController};

Route::middleware('auth')->group(function(){
    Route::get('/programs/{enrollment}',[StudentProgramController::class,'show'])->name('programs.show');
    Route::post('/learning/{section}/complete',[LearningController::class,'complete'])->name('learning.complete');
    Route::post('/practice/{assignment}/submit',[PracticeController::class,'submit'])->name('practice.submit');
});
