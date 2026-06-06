<?php

use App\Http\Controllers\Admin\ArticleController as AdminArticleController;
use App\Http\Controllers\Frontend\ArticleController as FrontendArticleController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Frontend\PageController;
use App\Http\Controllers\Frontend\ContactController;
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

    Route::get('/categories/{category}', 'showCategory')->name('categories.show');
});




// -----------------------------------------------------
// CONTACT CONTROLLER
// -----------------------------------------------------

Route::get('/contact', [ContactController::class, 'show'])->name('contact.show');
Route::post('/contact', [ContactController::class, 'store'])->middleware('throttle:5,1')->name('contact.store');






// -----------------------------------------------------
// ARTICLE CONTROLLER (ADMIN)
// -----------------------------------------------------

Route::controller(AdminArticleController::class)
    ->prefix('/articles')
    ->name('articles.')
    ->middleware(['auth'])
    ->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/create', 'create')->name('create');
        Route::post('/store', 'store')->name('store');
        Route::get('/{article}/edit', 'edit')->name('edit');
        Route::patch('/{article}', 'update')->name('update');
        Route::delete('/{article}','destroy')->name('destroy');
        Route::get('/{article}', 'inspect')->name('inspect');
        Route::post('/upload-image', 'uploadImage');
    })
;

// -----------------------------------------------------
// ARTICLE CONTROLLER (FRONT-END)
// -----------------------------------------------------

Route::controller(FrontendArticleController::class)
    ->group(function(){
        Route::get('/articles/{article}', 'show')->name('articles.show');
    })
;




// -----------------------------------------------------
// PROFILE CONTROLLER
// -----------------------------------------------------

Route::controller(ProfileController::class)
    ->prefix('/profile')
    ->name('profile.')
    ->middleware(['auth'])
    ->group(function () {
        Route::get('/', 'show')->name('show');
        Route::get('/edit', 'edit')->name('edit');
        Route::patch('/', 'update')->name('update');
        Route::delete('/', 'destroy')->name('destroy');
        Route::get('/password', 'editPassword')->name('password.edit');
        Route::patch('/password', 'updatePassword')->name('password.update');
        Route::post('/avatar', 'updateAvatar')->name('profile.avatar');
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

Route::controller(TranscriptionController::class)
    ->prefix('/transcriptions')
    ->name('transcriptions.')
    ->middleware(['auth', 'verified'])
    ->group(function(){
        Route::post('/store', 'store')->name('store');
        Route::get('/create', 'create')->name('create');
        Route::get('/transcriptions', 'index')->name('index');
        Route::post('/transcribe', 'transcribeVideo')->name('transcribe-youtube');
        Route::post('/transcribe-uploaded-video', 'transcribeVideo')->name('transcribe-upload');
        Route::get('/translate', 'translateSubtitles')->name('translate-subs');
});




require __DIR__.'/auth.php';
