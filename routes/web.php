<?php
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\QueueController;
use Illuminate\Support\Facades\Route;
Route::get('/',[QueueController::class,'home'])->name('queue.home');
Route::get('/locations/{location:slug}/display',[QueueController::class,'display'])->name('queue.display');
Route::get('/my-queue/{token}',[QueueController::class,'ticket'])->where('token','[A-Za-z0-9]{48}')->name('queue.ticket');
Route::post('/locations/{location:slug}/queue',[QueueController::class,'register'])->middleware('throttle:10,1')->name('queue.register');
Route::middleware('auth')->group(function(){
 Route::get('/dashboard',[QueueController::class,'dashboard'])->name('dashboard');
 Route::post('/staff/locations/{location}/next',[QueueController::class,'next'])->name('queue.next');
 Route::patch('/staff/tickets/{id}',[QueueController::class,'transition'])->whereNumber('id')->name('queue.transition');
 Route::get('/reports',[QueueController::class,'report'])->name('queue.report');
 Route::get('/profile',[ProfileController::class,'edit'])->name('profile.edit');Route::patch('/profile',[ProfileController::class,'update'])->name('profile.update');Route::delete('/profile',[ProfileController::class,'destroy'])->name('profile.destroy');
});
require __DIR__.'/auth.php';
