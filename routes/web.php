<?php

use App\Http\Controllers\ArticleController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TranscriptionController;
use Illuminate\Support\Facades\Route;




// -----------------------------------------------------
// PAGE CONTROLLER
// -----------------------------------------------------

Route::controller(PageController::class)->group(function(){
    Route::get('/', 'home')->name('home');
    Route::get('/about', 'about')->name('pages.about');
    Route::get('/contact', 'contact')->name('pages.contact');
    Route::get('/terms', 'terms')->name('pages.terms');
    Route::get('/privacy', 'privacy')->name('pages.privacy');
});




// -----------------------------------------------------
// ARTICLE CONTROLLER
// -----------------------------------------------------

Route::controller(ArticleController::class)->group(function(){
    Route::get('/articles/{hex}', 'show')->name('articles.show');
});



// -----------------------------------------------------
// PROFILE CONTROLLER
// -----------------------------------------------------

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});




// -----------------------------------------------------
// DASHBOARD CONTROLLER
// -----------------------------------------------------

Route::controller(DashboardController::class)->group(function(){
    Route::get('/dashboard', 'index')->middleware(['auth', 'verified'])->name('dashboard');
});




// -----------------------------------------------------
// TRANSCRIPTION CONTROLLER
// -----------------------------------------------------

Route::controller(TranscriptionController::class)->middleware('auth')->group(function(){
    Route::post('/dashboard/transcriptions/store', 'store')->name('transcriptions.store');
    Route::get('/dashboard/transcriptions/create', 'create')->name('transcriptions.create');
    Route::get('/dashboard/transcriptions', 'index')->name('transcriptions.index');


    Route::post('/admin/videos/transcribe', 'transcribeVideo')->name('admin.transcribe-youtube-video');
    Route::post('/admin/videos/transcribe-uploaded-video', 'transcribeVideo')->name('admin.transcribe-uploaded-video');
    Route::get('/admin/subtitles/translate', 'translateSubtitles')->name('admin.translate-subs');
});




require __DIR__.'/auth.php';
