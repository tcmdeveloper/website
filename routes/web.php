<?php

use App\Http\Controllers\Frontend\PageController;
use App\Http\Controllers\Frontend\ContactController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Frontend\CategoryController as FrontendCategoryController;
use App\Http\Controllers\Admin\ArticleController as AdminArticleController;
use App\Http\Controllers\Frontend\ArticleController as FrontendArticleController;
use App\Http\Controllers\Admin\TranscriptionController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\JailCallLogController;


// -----------------------------------------------------
// CRUD CONVENTION
// -----------------------------------------------------

// index   GET          /users
// show    GET          /users/{user}
// create  GET          /users/create
// store   POST         /users
// edit    GET          /users/{user}/edit
// update  PUT/PATCH    /users/{user}
// destroy DELETE /     users/{user}


Route::get('/import-csv', [JailCallLogController::class, 'showForm'])->middleware(['auth', 'verified'])->name('jail-call-logs.show');
Route::post('/import-csv', [JailCallLogController::class, 'import'])->middleware(['auth', 'verified'])->name('jail-call-logs.import');


// -----------------------------------------------------
// PAGE CONTROLLER
// -----------------------------------------------------

Route::get('/', [PageController::class, 'home'])->name('home');

Route::controller(PageController::class)
    ->name('pages.')
    ->group(function () {
        Route::get('/about', 'about')->name('about');
        Route::get('/contact', 'contact')->name('contact');
        Route::get('/terms', 'terms')->name('terms');
        Route::get('/privacy', 'privacy')->name('privacy');
    })
;


// -----------------------------------------------------
// CONTACT CONTROLLER
// -----------------------------------------------------

Route::controller(ContactController::class)
    ->prefix('contact')
    ->name('contact.')
    ->group(function () {
        Route::get('/', 'show')->name('show');
        Route::post('/', 'store')
            ->middleware('throttle:5,1') // Limited to 5 attempts in one minute (5,1)
            ->name('store');
    })
;


// -----------------------------------------------------
// DASHBOARD CONTROLLER
// -----------------------------------------------------

Route::controller(DashboardController::class)
    ->prefix('dashboard')
    ->name('dashboard.')
    ->middleware(['auth', 'verified'])
    ->group(function () {
        Route::get('/', 'index')->name('index');
    });


// -----------------------------------------------------
// PROFILE CONTROLLER
// -----------------------------------------------------

Route::controller(ProfileController::class)
    ->prefix('profile')
    ->name('profile.')
    ->middleware(['auth'])
    ->group(function () {
        Route::get('/', 'show')->name('show');
        Route::get('/edit', 'edit')->name('edit');
        Route::patch('/', 'update')->name('update');
        Route::delete('/', 'destroy')->name('destroy');
        Route::get('/password', 'editPassword')->name('password.edit');
        Route::patch('/password', 'updatePassword')->name('password.update');
        Route::post('/avatar', 'updateAvatar')->name('avatar');
    })
;


// -----------------------------------------------------
// CATEGORY CONTROLLER (FRONT-END)
// -----------------------------------------------------

Route::controller(FrontendCategoryController::class)
    ->prefix('categories')
    ->name('categories.')
    ->group(function(){
        Route::get('/', 'index')->name('index');
        Route::get('/{category}', 'show')->name('show');
        
    })
;


// -----------------------------------------------------
// CATEGORY CONTROLLER (ADMIN)
// -----------------------------------------------------

Route::controller(AdminCategoryController::class)
    ->prefix('admin/categories')
    ->name('admin.categories.')
    ->middleware(['auth'])
    ->group(function () {
        Route::get('/admin', 'index')->name('index');
        Route::get('/create', 'create')->name('create');
        Route::post('/store', 'store')->name('store');
        Route::get('/{article}/edit', 'edit')->name('edit');
        Route::patch('/{article}', 'update')->name('update');
        Route::delete('/{article}','destroy')->name('destroy');
        Route::get('/{article}/inspect', 'inspect')->name('inspect');
        Route::post('/upload-image', 'uploadImage');
    })
;


// -----------------------------------------------------
// ARTICLE CONTROLLER (FRONT-END)
// -----------------------------------------------------

Route::controller(FrontendArticleController::class)
    ->prefix('articles')
    ->name('articles.')
    ->group(function(){
        Route::get('/', 'index')->name('index');
        Route::get('/{article}', 'show')->name('show');
    })
;


// -----------------------------------------------------
// ARTICLE CONTROLLER (ADMIN)
// -----------------------------------------------------

Route::controller(AdminArticleController::class)
    ->prefix('admin/articles')
    ->name('admin.articles.')
    ->middleware(['auth', 'verified'])
    ->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/{article}', 'show')->name('show');
        Route::get('/create', 'create')->name('create');
        Route::post('/store', 'store')->name('store');
        Route::get('/{article}/edit', 'edit')->name('edit');
        Route::patch('/{article}', 'update')->name('update');
        Route::delete('/{article}','destroy')->name('destroy');
        Route::post('/upload-image', 'uploadImage');
    })
;


// -----------------------------------------------------
// TRANSCRIPTION CONTROLLER
// -----------------------------------------------------

Route::controller(TranscriptionController::class)
    ->prefix('transcriptions')
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

