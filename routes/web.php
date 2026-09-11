<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\AdmissionSessionController;
use App\Http\Controllers\Admin\BankAccountController;
use App\Http\Controllers\Admin\FeeConfigurationController;
use App\Http\Controllers\Admin\FeeTypeController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
})->name('home');


/*
|--------------------------------------------------------------------------
| Authenticated User Routes
|--------------------------------------------------------------------------
*/

Route::get('/dashboard', function () {
    return redirect()->route('admin.dashboard');
})->middleware(['auth'])->name('dashboard');

// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {

    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');

    Route::patch('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');

    Route::delete('/profile', [ProfileController::class, 'destroy'])
        ->name('profile.destroy');
});


/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        /*
        |--------------------------------------------------------------------------
        | Admin Dashboard
        |--------------------------------------------------------------------------
        */

        // Route::get('/', function () {
        //     return view('admin.dashboard');
        // })->name('dashboard');
        Route::get('/', [DashboardController::class, 'index'])
            ->name('dashboard');

        /*
        |--------------------------------------------------------------------------
        | Admission Sessions
        |--------------------------------------------------------------------------
        */

        Route::resource(
            'admission-sessions',
            AdmissionSessionController::class
        );

        Route::post(
            'admission-sessions/{admissionSession}/open',
            [AdmissionSessionController::class, 'open']
        )->name('admission-sessions.open');

        Route::post(
            'admission-sessions/{admissionSession}/close',
            [AdmissionSessionController::class, 'close']
        )->name('admission-sessions.close');


        /*
        |--------------------------------------------------------------------------
        | Bank Accounts
        |--------------------------------------------------------------------------
        */

        Route::resource(
            'bank-accounts',
            BankAccountController::class
        );


        /*
        |--------------------------------------------------------------------------
        | Fee Types
        |--------------------------------------------------------------------------
        */

        Route::resource(
            'fee-types',
            FeeTypeController::class
        );


        /*
        |--------------------------------------------------------------------------
        | Fee Configurations
        |--------------------------------------------------------------------------
        */

        Route::resource(
            'fee-configurations',
            FeeConfigurationController::class
        );


        Route::resource(
            'course-categories',
            \App\Http\Controllers\Admin\CourseCategoryController::class
        );

        Route::resource(
            'courses',
            \App\Http\Controllers\Admin\CourseController::class
        );

        Route::resource(
            'course-batches',
            \App\Http\Controllers\Admin\CourseBatchController::class
        );
    });


require __DIR__ . '/auth.php';