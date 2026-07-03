<?php

// PAGE
use App\Http\Controllers\Admin\ArticleController as AdminArticleController;
use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Admin\CriminalCaseController as AdminCriminalCaseController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\DocumentController as AdminDocumentController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\TranscriptionController;
use App\Http\Controllers\Admin\VideoController as AdminVideoController;
use App\Http\Controllers\Frontend\ArticleController as FrontendArticleController;
use App\Http\Controllers\Frontend\CategoryController as FrontendCategoryController;
use App\Http\Controllers\Frontend\ContactController;
use App\Http\Controllers\Frontend\CriminalCaseController as FrontendCriminalCaseController;
use App\Http\Controllers\Frontend\DocumentController as FrontendDocumentController;
use App\Http\Controllers\Frontend\PageController;
use App\Http\Controllers\Frontend\SearchController as FrontendSearchController;
use App\Http\Controllers\Frontend\VideoController as FrontendVideoController;
use App\Http\Controllers\JailCallLogController;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;


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


// Route::get('call-logs', [JailCallLogController::class, 'show'])->middleware(['auth', 'verified']);
// Route::get('call-logs/edit/{jailCallLog}', [JailCallLogController::class, 'edit'])->middleware(['auth', 'verified']);
// Route::patch('call-logs/patch', [JailCallLogController::class, 'update'])->middleware(['auth', 'verified']);



// Route::get('/import-csv', [JailCallLogController::class, 'showForm'])->middleware(['auth', 'verified'])->name('jail-call-logs.show');
// Route::post('/import-csv', [JailCallLogController::class, 'import'])->middleware(['auth', 'verified'])->name('jail-call-logs.import');


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
// CRIMINAL CASE CONTROLLER (FRONT-END)
// -----------------------------------------------------

Route::controller(FrontendCriminalCaseController::class)
    ->prefix('cases')
    ->name('cases.')
    ->group(function(){
        Route::get('/', 'index')->name('index');
        Route::get('/{criminalCase}', 'show')->name('show');
        
    })
;


// -----------------------------------------------------
// CRIMINAL CASE CONTROLLER (ADMIN)
// -----------------------------------------------------

Route::controller(AdminCriminalCaseController::class)
    ->prefix('admin/criminal-cases')
    ->name('admin.criminal-cases.')
    ->middleware(['auth'])
    ->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/create', 'create')->name('create');
        Route::post('/store', 'store')->name('store');
        Route::get('/{criminalCase}/edit', 'edit')->name('edit');
        Route::patch('/{criminalCase}', 'update')->name('update');
        Route::delete('/{criminalCase}','destroy')->name('destroy');
        Route::get('/{criminalCase}/inspect', 'inspect')->name('inspect');
        Route::post('/upload-image', 'uploadImage');

        Route::get('/{criminalCase}', 'show')->name('show');
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
        Route::get('/', 'index')->name('index');
        Route::get('/create', 'create')->name('create');
        Route::post('/store', 'store')->name('store');
        Route::get('/{category}/edit', 'edit')->name('edit');
        Route::patch('/{category}', 'update')->name('update');
        Route::delete('/{category}','destroy')->name('destroy');
        Route::get('/{category}/inspect', 'inspect')->name('inspect');
        Route::post('/upload-image', 'uploadImage');

        Route::get('/{category}', 'show')->name('show');
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
        Route::get('/create', 'create')->name('create');
        Route::post('/store', 'store')->name('store');
        Route::get('/{article}/edit', 'edit')->name('edit');
        Route::patch('/{article}', 'update')->name('update');
        Route::delete('/{article}', 'destroy')->name('destroy');

        Route::patch('/{article}/image/{image}/update', 'updateImage')->name('images.update');
        Route::delete('/{article}/images/{image}', 'destroyImage')->name('images.destroy');
        Route::get('/{article}/images/{image}/edit', 'editImage')->name('images.edit');
        Route::post('/{article}/images/store', 'storeImage')->name('images.store');
        Route::get('/{article}/images/upload', 'selectImage')->name('images.upload');
        Route::get('/{article}/images', 'imagesIndex')->name('images');
        

        Route::get('/{article}', 'show')->name('show');
    })
;


// -----------------------------------------------------
// DOCUMENT CONTROLLER (FRONT-END)
// -----------------------------------------------------

Route::controller(FrontendDocumentController::class)
    ->prefix('cases/{criminalCase:slug}/documents')
    ->name('documents.')
    ->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/{document:slug}', 'show')->name('show');
    });


// -----------------------------------------------------
// DOCUMENT CONTROLLER (ADMIN)
// -----------------------------------------------------

Route::controller(AdminDocumentController::class)
    ->prefix('admin/documents')
    ->name('admin.documents.')
    ->middleware(['auth', 'verified'])
    ->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/create', 'create')->name('create');
        Route::post('/store', 'store')->name('store');
        Route::get('/{document}/edit', 'edit')->name('edit');
        Route::patch('/{document}', 'update')->name('update');
        Route::delete('/{document}', 'destroy')->name('destroy');

        Route::patch('/{document}/image/{image}/update', 'updateImage')->name('images.update');
        Route::delete('/{document}/images/{image}', 'destroyImage')->name('images.destroy');
        Route::get('/{document}/images/{image}/edit', 'editImage')->name('images.edit');
        Route::post('/{document}/images/store', 'storeImage')->name('images.store');
        Route::get('/{document}/images/upload', 'selectImage')->name('images.upload');
        Route::get('/{document}/images', 'imagesIndex')->name('images');


        Route::get('/{document}', 'show')->name('show');
    })
;


// -----------------------------------------------------
// SEARCH CONTROLLER (FRONT-END)
// -----------------------------------------------------

Route::controller(FrontendSearchController::class)
    ->prefix('search')
    ->name('search.')
    ->group(function(){
        Route::get('/', 'index')->name('index');
    })
;


// -----------------------------------------------------
// VIDEO CONTROLLER (ADMIN)
// -----------------------------------------------------

Route::controller(AdminVideoController::class)
    ->prefix('admin/videos')
    ->name('admin.videos.')
    ->middleware(['auth', 'verified'])
    ->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/create', 'create')->name('create');
        Route::post('/store', 'store')->name('store');
        Route::get('/{video}/edit', 'edit')->name('edit');
        Route::get('/{video}/transcribe', 'transcribe')->name('transcribe');
        Route::delete('/{video}', 'destroy')->name('destroy');

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

