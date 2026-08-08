<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\ContactController;

/*
|--------------------------------------------------------------------------
| საჯარო საიტის გვერდები
|--------------------------------------------------------------------------
*/

Route::view('/', 'user.pages.main')
    ->name('home');

Route::view('/company', 'user.pages.company')
    ->name('company');

Route::view('/services', 'user.pages.services')
    ->name('services');

Route::get('/projects', [ProjectController::class, 'index'])
    ->name('projects');

Route::get('/projects/{slug}', [ProjectController::class, 'show'])
    ->name('projects.show');

Route::view('/media', 'user.pages.media')
    ->name('media');

Route::view('/contact', 'user.pages.contact')
    ->name('contact');

Route::post('/contact', [ContactController::class, 'store'])
    ->middleware('throttle:5,1')
    ->name('contact.store');


/*
|--------------------------------------------------------------------------
| ადმინისტრატორის გვერდები
|--------------------------------------------------------------------------
*/

Route::prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::view('/', 'admin.pages.main')
            ->name('dashboard');
    });
