<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{LearningController,PracticeController,QuizController,StudentProgramController,ProfileController,FinalWorkController,HistoryController,DocumentController,StudentServicesController,PublicSiteController};
use App\Http\Controllers\Curator\MessageController as CuratorMessageController;
use App\Http\Controllers\Curator\ArchiveController as CuratorArchiveController;
use App\Http\Controllers\Admin\QuizNotificationController;

Route::middleware('auth')->group(function(){
    Route::post('/courses/{program}/enroll',[PublicSiteController::class,'enroll'])->name('public.program.enroll');

    Route::get('/programs/{enrollment}',[StudentProgramController::class,'show'])->name('programs.show');
    Route::post('/programs/{enrollment}/complete',[StudentProgramController::class,'complete'])->name('programs.complete');

    Route::get('/profile',[ProfileController::class,'show'])->name('profile.show');
    Route::put('/profile',[ProfileController::class,'update'])->name('profile.update');

    Route::get('/final-works',[FinalWorkController::class,'index'])->name('final-works.index');
    Route::post('/final-works/{enrollment}',[FinalWorkController::class,'submit'])->name('final-works.submit');
    Route::get('/final-work-files/{finalWork}',[FinalWorkController::class,'download'])->name('final-works.download');

    Route::get('/history',[HistoryController::class,'index'])->name('history.index');
    Route::get('/documents/{document}/download',[DocumentController::class,'download'])->name('documents.download');
    Route::get('/quiz-attempts/{attempt}/result',[QuizController::class,'result'])->name('quizzes.result');

    Route::post('/learning/{section}/complete',[LearningController::class,'complete'])->name('learning.complete');
    Route::get('/learning-content/{item}/download',[LearningController::class,'download'])->name('learning.download');
    Route::post('/practice/{assignment}/submit',[PracticeController::class,'submit'])->name('practice.submit');
    Route::get('/practice-submissions/{submission}/download',[PracticeController::class,'download'])->name('practice.download');

    Route::prefix('services')->name('student.')->group(function(){
        Route::get('/messages',[StudentServicesController::class,'messages'])->name('messages');
        Route::post('/messages/{enrollment}',[StudentServicesController::class,'sendMessage'])->name('messages.send');
        Route::get('/schedule',[StudentServicesController::class,'schedule'])->name('schedule');
        Route::post('/schedule/{slot}',[StudentServicesController::class,'bookSlot'])->name('schedule.book');
        Route::delete('/schedule/{slot}',[StudentServicesController::class,'cancelSlot'])->name('schedule.cancel');
        Route::get('/surveys',[StudentServicesController::class,'surveys'])->name('surveys');
        Route::post('/surveys/{survey}',[StudentServicesController::class,'submitSurvey'])->name('surveys.submit');
        Route::get('/resources',[StudentServicesController::class,'resources'])->name('resources');
        Route::get('/resources/{resource}/download',[StudentServicesController::class,'downloadResource'])->name('resources.download');
    });
});

Route::prefix('curator')->name('curator.')->middleware(['auth','role:curator,admin'])->group(function(){
    Route::get('/messages',[CuratorMessageController::class,'index'])->name('messages');
    Route::post('/messages/{enrollment}',[CuratorMessageController::class,'send'])->name('messages.send');
    Route::get('/legacy-logins',[CuratorArchiveController::class,'logins'])->name('legacy-logins');
    Route::get('/legacy-announcements',[CuratorArchiveController::class,'announcements'])->name('legacy-announcements');
    Route::post('/legacy-announcements',[CuratorArchiveController::class,'storeAnnouncement'])->name('legacy-announcements.store');
});

Route::post('/admin/quizzes/{assignment}/notify-open',[QuizNotificationController::class,'send'])
    ->middleware(['auth','role:admin'])->name('admin.quizzes.notify');
