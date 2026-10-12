<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PaymentController;
Route::middleware('auth:sanctum')->get('/user',fn(Request $request)=>$request->user());

Route::post('/payments/robokassa/result',[PaymentController::class,'result'])->name('payments.robokassa.result');
