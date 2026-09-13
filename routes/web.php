<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\AdmissionSessionController;
use App\Http\Controllers\Admin\BankAccountController;
use App\Http\Controllers\Admin\FeeConfigurationController;
use App\Http\Controllers\Admin\FeeTypeController;
use App\Http\Controllers\PublicController;
use App\Http\Controllers\PublicAdmissionController;


/*
|--------------------------------------------------------------------------
| Public Website
|--------------------------------------------------------------------------
*/

Route::get('/', [PublicController::class, 'home'])->name('home');

Route::get('/courses', [PublicController::class, 'courses'])
    ->name('public.courses');

Route::get('/facilities', [PublicController::class, 'facilities'])
    ->name('public.facilities');

Route::get('/gallery', [PublicController::class, 'gallery'])
    ->name('public.gallery');

Route::get('/events', [PublicController::class, 'events'])
    ->name('public.events');

Route::get('/announcements', [PublicController::class, 'announcements'])
    ->name('public.announcements');

Route::get('/alumni', [PublicController::class, 'alumni'])
    ->name('public.alumni');

Route::get('/alumni/register', [PublicController::class, 'alumniRegister'])
    ->name('public.alumni.register');

Route::post('/alumni/register', [PublicController::class, 'storeAlumni'])
    ->name('public.alumni.store');

Route::get('/contact', [PublicController::class, 'contact'])
    ->name('public.contact');

Route::post('/contact', [PublicController::class, 'submitContact'])
    ->name('public.contact.submit');



Route::get('/apply-online', [
    PublicAdmissionController::class,
    'create',
])->name('public.admission');

Route::post('/apply-online', [
    PublicAdmissionController::class,
    'store',
])->name('public.admission.store');

Route::get('/apply-online/voucher/{voucher}', [
    PublicAdmissionController::class,
    'voucher',
])->name('public.admission.voucher');


/*
|--------------------------------------------------------------------------
| Authentication
|--------------------------------------------------------------------------
*/

Route::get('/dashboard', function () {
    return redirect()->route('admin.dashboard');
})->middleware('auth')->name('dashboard');

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
        Route::get('/', [DashboardController::class, 'index'])
            ->name('dashboard');

        Route::resource('admission-sessions', AdmissionSessionController::class);

        Route::post(
            'admission-sessions/{admissionSession}/open',
            [AdmissionSessionController::class, 'open']
        )->name('admission-sessions.open');

        Route::post(
            'admission-sessions/{admissionSession}/close',
            [AdmissionSessionController::class, 'close']
        )->name('admission-sessions.close');

        Route::resource('bank-accounts', BankAccountController::class);
        Route::resource('fee-types', FeeTypeController::class);
        Route::resource('fee-configurations', FeeConfigurationController::class);

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