<?php

use Illuminate\Support\Facades\Route;

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

Route::view('/projects', 'user.pages.projects')
    ->name('projects');

Route::view('/media', 'user.pages.media')
    ->name('media');

Route::view('/contact', 'user.pages.contact')
    ->name('contact');


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
