<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\CompanyPageController;
use App\Http\Controllers\ServicesPageController;
use App\Http\Controllers\MediaPageController;
use App\Http\Controllers\ContactPageController;
use App\Http\Controllers\Admin\ContactSettingsController;
use App\Http\Controllers\Admin\MediaController;
use App\Http\Controllers\Admin\MediaVideoController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\CompanyController;
use App\Http\Controllers\Admin\EmployeeController;
use App\Http\Controllers\Admin\PartnerController;
use App\Http\Controllers\Admin\PasswordController;
use App\Http\Controllers\Admin\ProjectController as AdminProjectController;
use App\Http\Controllers\Admin\ServiceStepController;

/*
|--------------------------------------------------------------------------
| საჯარო საიტის გვერდები
|--------------------------------------------------------------------------
*/

Route::view('/', 'user.pages.main')
    ->name('home');

Route::get('/company', CompanyPageController::class)
    ->name('company');

Route::get('/services', ServicesPageController::class)
    ->name('services');

Route::get('/projects', [ProjectController::class, 'index'])
    ->name('projects');

Route::get('/projects/{slug}', [ProjectController::class, 'show'])
    ->name('projects.show');

Route::get('/media', MediaPageController::class)
    ->name('media');

Route::get('/contact', ContactPageController::class)
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
        Route::middleware('guest')->group(function () {
            Route::get('/login', [AuthController::class, 'showLogin'])
                ->name('login');

            Route::post('/login', [AuthController::class, 'login'])
                ->middleware('throttle:5,1')
                ->name('login.attempt');
        });

        Route::middleware('auth')->group(function () {
            Route::view('/', 'admin.pages.main')
                ->name('dashboard');

            Route::put('/password', [PasswordController::class, 'update'])
                ->middleware('throttle:6,1')
                ->name('password.update');

            Route::get('/company', [CompanyController::class, 'index'])
                ->name('company');

            Route::resource('company/employees', EmployeeController::class)
                ->only(['store', 'edit', 'update', 'destroy'])
                ->names('company.employees');

            Route::resource('company/partners', PartnerController::class)
                ->only(['store', 'edit', 'update', 'destroy'])
                ->names('company.partners');

            Route::get('/services', [ServiceStepController::class, 'index'])
                ->name('services');

            Route::resource('services/steps', ServiceStepController::class)
                ->only(['store', 'edit', 'update', 'destroy'])
                ->parameters(['steps' => 'step'])
                ->names('services.steps');

            Route::get('/projects', [AdminProjectController::class, 'index'])
                ->name('projects');

            Route::resource('projects/items', AdminProjectController::class)
                ->only(['store', 'edit', 'update', 'destroy'])
                ->parameters(['items' => 'project'])
                ->names('projects.items');

            Route::get('/media', [MediaController::class, 'index'])
                ->name('media');

            Route::post('/media/photos', [MediaController::class, 'storePhotos'])
                ->name('media.photos.store');

            Route::delete('/media/photos', [MediaController::class, 'destroyPhotos'])
                ->name('media.photos.destroy');

            Route::resource('media/videos', MediaVideoController::class)
                ->only(['store', 'edit', 'update', 'destroy'])
                ->names('media.videos');

            Route::get('/contact', [ContactSettingsController::class, 'edit'])
                ->name('contact');

            Route::put('/contact', [ContactSettingsController::class, 'update'])
                ->name('contact.update');

            Route::post('/logout', [AuthController::class, 'logout'])
                ->name('logout');
        });
    });
