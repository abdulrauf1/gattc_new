<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\HomeController;
use App\Http\Controllers\PublicAdmissionController;

use App\Http\Controllers\Admin\AdmissionController;
use App\Http\Controllers\Admin\AdmissionSessionController;
use App\Http\Controllers\Admin\BankAccountController;
use App\Http\Controllers\Admin\CourseCategoryController;
use App\Http\Controllers\Admin\CourseController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\FeePaymentController;
use App\Http\Controllers\Admin\VoucherController;


/*
|--------------------------------------------------------------------------
| PUBLIC WEBSITE
|--------------------------------------------------------------------------
*/

/*
|--------------------------------------------------------------------------
| Homepage
|--------------------------------------------------------------------------
*/

Route::get('/', [
    HomeController::class,
    'index',
])->name('home');


/*
|--------------------------------------------------------------------------
| Public Courses
|--------------------------------------------------------------------------
*/

Route::get('/courses', function () {

    $courses = \App\Models\Course::query()
        ->where('status', true)
        ->with([
            'category',
            'bankAccount',
        ])
        ->orderBy('sort_order')
        ->orderBy('title')
        ->get();

    return view('public.courses', [
        'courses' => $courses,
    ]);

})->name('public.courses');


Route::get('/courses/{course}', function (
    \App\Models\Course $course
) {

    abort_unless($course->status, 404);

    $course->load([
        'category',
        'bankAccount',
    ]);

    return view('public.course-details', [
        'course' => $course,
    ]);

})->name('public.course.show');


/*
|--------------------------------------------------------------------------
| Public Facilities
|--------------------------------------------------------------------------
*/

Route::view(
    '/facilities',
    'public.facilities'
)->name('public.facilities');


/*
|--------------------------------------------------------------------------
| Public Gallery
|--------------------------------------------------------------------------
*/

Route::view(
    '/gallery',
    'public.gallery'
)->name('public.gallery');


/*
|--------------------------------------------------------------------------
| Public Events
|--------------------------------------------------------------------------
*/

Route::view(
    '/events',
    'public.events'
)->name('public.events');


/*
|--------------------------------------------------------------------------
| Public Announcements
|--------------------------------------------------------------------------
*/

Route::view(
    '/announcements',
    'public.announcements'
)->name('public.announcements');


/*
|--------------------------------------------------------------------------
| Public News
|--------------------------------------------------------------------------
|
| Keep /news for compatibility with existing links.
|
*/

Route::redirect(
    '/news',
    '/announcements'
)->name('public.news');


/*
|--------------------------------------------------------------------------
| Public Alumni
|--------------------------------------------------------------------------
*/

Route::view(
    '/alumni',
    'public.alumni'
)->name('public.alumni');


Route::view(
    '/alumni/register',
    'public.alumni-register'
)->name('public.alumni.register');

/*
|--------------------------------------------------------------------------
| Public Contact
|--------------------------------------------------------------------------
*/

Route::view(
    '/contact',
    'public.contact'
)->name('public.contact');


/*
|--------------------------------------------------------------------------
| PUBLIC ONLINE ADMISSION
|--------------------------------------------------------------------------
*/

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
| PUBLIC PAYMENT SUBMISSION
|--------------------------------------------------------------------------
*/

Route::post('/payment-submit', [
    FeePaymentController::class,
    'submit',
])->name('public.payment.submit');


/*
|--------------------------------------------------------------------------
| ADMIN PORTAL
|--------------------------------------------------------------------------
*/

/*
|--------------------------------------------------------------------------
| Authenticated Dashboard
|--------------------------------------------------------------------------
*/

Route::get('/dashboard', function () {
    return redirect()->route('admin.dashboard');
})->middleware(['auth'])->name('dashboard');



Route::middleware(['auth'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        /*
        |--------------------------------------------------------------------------
        | Dashboard
        |--------------------------------------------------------------------------
        */

        Route::get('/dashboard', [
            DashboardController::class,
            'index',
        ])->name('dashboard');


        /*
        |--------------------------------------------------------------------------
        | COURSE MANAGEMENT
        |--------------------------------------------------------------------------
        */

        Route::resource(
            'courses',
            CourseController::class
        );


        /*
        |--------------------------------------------------------------------------
        | COURSE CATEGORY MANAGEMENT
        |--------------------------------------------------------------------------
        */

        Route::resource(
            'course-categories',
            CourseCategoryController::class
        );


        /*
        |--------------------------------------------------------------------------
        | ADMISSION SESSION MANAGEMENT
        |--------------------------------------------------------------------------
        */

        Route::resource(
            'admission-sessions',
            AdmissionSessionController::class
        );


        /*
        |--------------------------------------------------------------------------
        | Open Admission Session
        |--------------------------------------------------------------------------
        */

        Route::post(
            '/admission-sessions/{admissionSession}/open',
            [
                AdmissionSessionController::class,
                'open',
            ]
        )->name('admission-sessions.open');


        /*
        |--------------------------------------------------------------------------
        | Close Admission Session
        |--------------------------------------------------------------------------
        */

        Route::post(
            '/admission-sessions/{admissionSession}/close',
            [
                AdmissionSessionController::class,
                'close',
            ]
        )->name('admission-sessions.close');


        /*
        |--------------------------------------------------------------------------
        | BANK ACCOUNTS
        |--------------------------------------------------------------------------
        */

        Route::resource(
            'bank-accounts',
            BankAccountController::class
        );


        /*
        |--------------------------------------------------------------------------
        | VOUCHERS
        |--------------------------------------------------------------------------
        */

        /*
         * Keep this custom route before the resource route.
         */
        Route::post(
            '/vouchers/{voucher}/create-admission',
            [
                AdmissionController::class,
                'createFromVoucher',
            ]
        )->name('vouchers.create-admission');


        Route::resource(
            'vouchers',
            VoucherController::class
        )->only([
            'index',
            'create',
            'store',
            'show',
            'destroy',
        ]);


        /*
        |--------------------------------------------------------------------------
        | ADMISSIONS
        |--------------------------------------------------------------------------
        */

        Route::get('/admissions', [
            AdmissionController::class,
            'index',
        ])->name('admissions.index');

        Route::get('/admissions/{admission}', [
            AdmissionController::class,
            'show',
        ])->name('admissions.show');

        Route::patch('/admissions/{admission}/status', [
            AdmissionController::class,
            'updateStatus',
        ])->name('admissions.status');

        /*
        |--------------------------------------------------------------------------
        | PAYMENT VERIFICATION
        |--------------------------------------------------------------------------
        */

        Route::get('/fee-payments', [
            FeePaymentController::class,
            'index',
        ])->name('fee-payments.index');


        Route::get(
            '/fee-payments/{feePayment}',
            [
                FeePaymentController::class,
                'show',
            ]
        )->name('fee-payments.show');


        Route::post(
            '/fee-payments/{feePayment}/approve',
            [
                FeePaymentController::class,
                'approve',
            ]
        )->name('fee-payments.approve');


        Route::post(
            '/fee-payments/{feePayment}/reject',
            [
                FeePaymentController::class,
                'reject',
            ]
        )->name('fee-payments.reject');


        Route::get(
            '/fee-payments/{feePayment}/slip',
            [
                FeePaymentController::class,
                'slip',
            ]
        )->name('fee-payments.slip');
    });


/*
|--------------------------------------------------------------------------
| PROFILE
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->group(function () {

    Route::get('/profile', [
        \App\Http\Controllers\ProfileController::class,
        'edit',
    ])->name('profile.edit');


    Route::patch('/profile', [
        \App\Http\Controllers\ProfileController::class,
        'update',
    ])->name('profile.update');


    Route::delete('/profile', [
        \App\Http\Controllers\ProfileController::class,
        'destroy',
    ])->name('profile.destroy');
});


/*
|--------------------------------------------------------------------------
| AUTHENTICATION
|--------------------------------------------------------------------------
*/

require __DIR__ . '/auth.php';