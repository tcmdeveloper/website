<?php

use App\Http\Controllers\Admin\ArticleController as AdminArticleController;
use App\Http\Controllers\Frontend\ArticleController as FrontendArticleController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Frontend\PageController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\TranscriptionController;
use Illuminate\Support\Facades\Route;

// CRUD CONVENTION
// admin.articles.index
// admin.articles.create
// admin.articles.store
// admin.articles.show
// admin.articles.edit
// admin.articles.update
// admin.articles.destroy

// -----------------------------------------------------
// PAGE CONTROLLER
// -----------------------------------------------------

Route::controller(PageController::class)->group(function(){
    Route::get('/', 'home')->name('home');
    Route::get('/about', 'about')->name('pages.about');
    Route::get('/contact', 'contact')->name('pages.contact');
    Route::get('/terms', 'terms')->name('pages.terms');
    Route::get('/privacy', 'privacy')->name('pages.privacy');

    Route::get('/posts', 'privacy')->name('posts.index');
    Route::get('/case-updates', 'privacy')->name('case-updates.index');
    Route::get('/documents', 'privacy')->name('documents.index');
    Route::get('/criminal-profiles', 'privacy')->name('criminal-profiles.index');
});




// -----------------------------------------------------
// ARTICLE CONTROLLER (FRONT-END)
// -----------------------------------------------------

Route::controller(FrontendArticleController::class)
    ->group(function(){
        Route::get('/articles/{article}', 'show')->name('articles.show');
    })
;


// -----------------------------------------------------
// ARTICLE CONTROLLER (ADMIN)
// -----------------------------------------------------

Route::controller(AdminArticleController::class)
    ->middleware(['auth', 'verified'])
    ->group(function(){
        Route::get('/admin/articles', 'index')->name('admin.articles.index');
        
        Route::get('/admin/articles/create', 'create')->name('admin.articles.create');
        Route::post('/admin/articles/store', 'store')->name('articles.store');
        Route::get('/admin/articles/{article}/edit', 'edit')->name('admin.articles.edit');
        Route::patch('/admin/articles/{article}', 'update')->name('admin.articles.update');
        Route::delete('/admin/articles/{article}','destroy')->name('admin.articles.destroy');

        Route::get('/admin/articles/{article}', 'inspect')->name('admin.articles.inspect');


        Route::post('/admin/articles/upload-image', 'uploadImage');
    })
;




// -----------------------------------------------------
// PROFILE CONTROLLER
// -----------------------------------------------------

Route::controller(ProfileController::class)->prefix('admin/profile')->name('admin.profile.')->middleware(['auth'])->group(function () {
    Route::get('/', 'show')->name('show');
    Route::get('/edit', 'edit')->name('edit');
    Route::patch('/', 'update')->name('update');
    Route::delete('/', 'destroy')->name('destroy');

    Route::get('/password', 'editPassword')->name('password.edit');
    Route::patch('/password', 'updatePassword')->name('password.update');

    Route::post('/avatar', 'updateAvatar')->name('admin.profile.avatar');
});




// -----------------------------------------------------
// DASHBOARD CONTROLLER
// -----------------------------------------------------

Route::controller(DashboardController::class)->group(function(){
    Route::get('/dashboard', 'index')->middleware(['auth', 'verified'])->name('admin.dashboard');
});




// -----------------------------------------------------
// TRANSCRIPTION CONTROLLER
// -----------------------------------------------------

Route::controller(TranscriptionController::class)->middleware(['auth', 'verified'])->group(function(){
    Route::post('/admin/transcriptions/store', 'store')->name('admin.transcriptions.store');
    Route::get('/admin/transcriptions/create', 'create')->name('admin.transcriptions.create');
    Route::get('/admin/transcriptions', 'index')->name('admin.transcriptions.index');


    Route::post('/admin/videos/transcribe', 'transcribeVideo')->name('admin.transcriptions.transcribe-youtube');
    Route::post('/admin/videos/transcribe-uploaded-video', 'transcribeVideo')->name('admin.transcriptions.transcribe-upload');
    Route::get('/admin/subtitles/translate', 'translateSubtitles')->name('admin.transcriptions.translate-subs');
});




require __DIR__.'/auth.php';
