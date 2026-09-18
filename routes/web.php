<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public Controllers
|--------------------------------------------------------------------------
*/

use App\Http\Controllers\HomeController;
use App\Http\Controllers\PublicAdmissionController;
use App\Http\Controllers\PublicContactController;
use App\Http\Controllers\PublicController;
use App\Http\Controllers\PublicPaymentController;
/*
|--------------------------------------------------------------------------
| Admin Controllers
|--------------------------------------------------------------------------
*/

use App\Http\Controllers\Admin\AdmissionController;
use App\Http\Controllers\Admin\AdmissionSessionController;
use App\Http\Controllers\Admin\AlumniController;
use App\Http\Controllers\Admin\AnnouncementController;
use App\Http\Controllers\Admin\BankAccountController;
use App\Http\Controllers\Admin\CourseCategoryController;
use App\Http\Controllers\Admin\CourseController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\EventController;
use App\Http\Controllers\Admin\FeePaymentController;
use App\Http\Controllers\Admin\GalleryController;
use App\Http\Controllers\Admin\VoucherController;
use App\Http\Controllers\Admin\WebsiteSettingController;
use App\Http\Controllers\Admin\ContactMessageController;
use App\Http\Controllers\Admin\UserController;


/*
|--------------------------------------------------------------------------
| PUBLIC WEBSITE
|--------------------------------------------------------------------------
*/


/*
|--------------------------------------------------------------------------
| PUBLIC WEBSITE
|--------------------------------------------------------------------------
*/

Route::get('/', [
    PublicController::class,
    'home',
])->name('home');


Route::get('/courses', [
    PublicController::class,
    'courses',
])->name('public.courses');


Route::get('/courses/{slug}', [
    PublicController::class,
    'course',
])->name('public.course.show');


Route::get('/facilities', [
    PublicController::class,
    'facilities',
])->name('public.facilities');


Route::get('/gallery', [
    PublicController::class,
    'gallery',
])->name('public.gallery');


Route::get('/events', [
    PublicController::class,
    'events',
])->name('public.events');


Route::get('/announcements', [
    PublicController::class,
    'announcements',
])->name('public.announcements');


Route::redirect(
    '/news',
    '/announcements'
)->name('public.news');


Route::get('/alumni', [
    PublicController::class,
    'alumni',
])->name('public.alumni');


Route::get('/alumni/register', [
    PublicController::class,
    'alumniRegister',
])->name('public.alumni.register');


Route::post('/alumni/register', [
    PublicController::class,
    'storeAlumni',
])->name('public.alumni.register.store');


Route::get('/contact', [
    PublicController::class,
    'contact',
])->name('public.contact');


Route::post('/contact', [
    PublicController::class,
    'storeContact',
])->name('public.contact.store');


/*
|--------------------------------------------------------------------------
| ONLINE ADMISSION
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
    PublicPaymentController::class,
    'store',
])->name('public.payment.submit');

/*
|--------------------------------------------------------------------------
| ADMIN PORTAL
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'active.user'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {


        /*
        |--------------------------------------------------------------------------
        | DASHBOARD
        |--------------------------------------------------------------------------
        */

        Route::get('/dashboard', [
            DashboardController::class,
            'index',
        ])->name('dashboard');


        /*
        |--------------------------------------------------------------------------
        | User Management
        |--------------------------------------------------------------------------
        */
        Route::get('/users', [UserController::class, 'index'])
            ->middleware('permission:users.view')
            ->name('users.index');

        Route::get('/users/create', [UserController::class, 'create'])
            ->middleware('permission:users.create')
            ->name('users.create');

        Route::post('/users', [UserController::class, 'store'])
            ->middleware('permission:users.create')
            ->name('users.store');

        Route::get('/users/{user}', [UserController::class, 'show'])
            ->middleware('permission:users.view')
            ->name('users.show');

        Route::get('/users/{user}/edit', [UserController::class, 'edit'])
            ->middleware('permission:users.edit')
            ->name('users.edit');

        Route::put('/users/{user}', [UserController::class, 'update'])
            ->middleware('permission:users.edit')
            ->name('users.update');

        Route::delete('/users/{user}', [UserController::class, 'destroy'])
            ->middleware('permission:users.delete')
            ->name('users.destroy');

        Route::patch('/users/{user}/activate', [UserController::class, 'activate'])
            ->middleware('permission:users.activate')
            ->name('users.activate');

        Route::patch('/users/{user}/deactivate', [UserController::class, 'deactivate'])
            ->middleware('permission:users.activate')
            ->name('users.deactivate');

        Route::patch('/users/{user}/password', [UserController::class, 'updatePassword'])
            ->middleware('permission:users.password')
            ->name('users.password');

        /*
        |--------------------------------------------------------------------------
        | ACADEMICS
        |--------------------------------------------------------------------------
        */


        /*
        | Course Categories
        */

        Route::resource(
            'course-categories',
            CourseCategoryController::class
        );


        /*
        | Courses
        */

        Route::resource(
            'courses',
            CourseController::class
        );


        /*
        |--------------------------------------------------------------------------
        | ADMISSION SESSIONS
        |--------------------------------------------------------------------------
        */

        Route::resource(
            'admission-sessions',
            AdmissionSessionController::class
        );


        Route::post(
            '/admission-sessions/{admissionSession}/open',
            [
                AdmissionSessionController::class,
                'open',
            ]
        )->name(
            'admission-sessions.open'
        );


        Route::post(
            '/admission-sessions/{admissionSession}/close',
            [
                AdmissionSessionController::class,
                'close',
            ]
        )->name(
            'admission-sessions.close'
        );


        /*
        |--------------------------------------------------------------------------
        | ADMISSIONS
        |--------------------------------------------------------------------------
        */


        /*
        | Admissions listing
        */

        Route::get('/admissions', [
            AdmissionController::class,
            'index',
        ])->name('admissions.index');


        /*
        | Admission details
        */

        Route::get(
            '/admissions/{admission}',
            [
                AdmissionController::class,
                'show',
            ]
        )->name('admissions.show');


        /*
        | Update admission status manually
        */

        Route::patch(
            '/admissions/{admission}/status',
            [
                AdmissionController::class,
                'updateStatus',
            ]
        )->name(
            'admissions.status'
        );


        /*
        | Generate/reissue Student Card
        |
        | Student cards are generated ONLY from Admissions.
        */

        Route::post(
            '/admissions/{admission}/student-card',
            [
                AdmissionController::class,
                'generateStudentCard',
            ]
        )->name(
            'admissions.student-card'
        );


        /*
        |--------------------------------------------------------------------------
        | VOUCHERS
        |--------------------------------------------------------------------------
        */


        /*
        | Print three-copy challan
        |
        | Put this before resource routes.
        */

        Route::get(
            '/vouchers/{voucher}/print',
            [
                VoucherController::class,
                'print',
            ]
        )->name(
            'vouchers.print'
        );


        /*
        | Generate/view/delete vouchers
        */

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
        | PAYMENT VERIFICATION
        |--------------------------------------------------------------------------
        |
        | Voucher-centric workflow:
        |
        | All vouchers appear here.
        |
        | No payment:
        |     Not Submitted
        |
        | Payment exists:
        |     Pending
        |     Approved
        |     Rejected
        |
        */


        /*
        | All generated vouchers / payment verification
        */

        Route::get(
            '/fee-payments',
            [
                FeePaymentController::class,
                'index',
            ]
        )->name(
            'fee-payments.index'
        );


        /*
        | Open payment verification for a voucher
        */

        Route::get(
            '/fee-payments/voucher/{voucher}',
            [
                FeePaymentController::class,
                'showVoucher',
            ]
        )->name(
            'fee-payments.voucher'
        );


        /*
        | Enter/update deposited payment information
        */

        Route::patch(
            '/fee-payments/voucher/{voucher}/details',
            [
                FeePaymentController::class,
                'updateDetails',
            ]
        )->name(
            'fee-payments.update-details'
        );


        /*
        | Approve payment
        */

        Route::post(
            '/fee-payments/voucher/{voucher}/approve',
            [
                FeePaymentController::class,
                'approve',
            ]
        )->name(
            'fee-payments.approve'
        );


        /*
        | Reject payment
        */

        Route::post(
            '/fee-payments/voucher/{voucher}/reject',
            [
                FeePaymentController::class,
                'reject',
            ]
        )->name(
            'fee-payments.reject'
        );


        /*
        | Open uploaded paid bank slip
        */

        Route::get(
            '/fee-payments/{feePayment}/slip',
            [
                FeePaymentController::class,
                'slip',
            ]
        )->name(
            'fee-payments.slip'
        );


        /*
        |--------------------------------------------------------------------------
        | STUDENT CARDS
        |--------------------------------------------------------------------------
        |
        | Printing only.
        | Generation is handled from Admissions.
        |
        */

        Route::get(
            '/student-cards/{studentCard}/print',
            [
                AdmissionController::class,
                'printStudentCard',
            ]
        )->name(
            'student-cards.print'
        );


        /*
        |--------------------------------------------------------------------------
        | WEBSITE MANAGEMENT
        |--------------------------------------------------------------------------
        */

        Route::get('/website-settings', [
            WebsiteSettingController::class,
            'index',
        ])->name('website-settings.index');

        Route::put('/website-settings', [
            WebsiteSettingController::class,
            'update',
        ])->name('website-settings.update');

        /*
        |--------------------------------------------------------------------------
        | Gallery
        |--------------------------------------------------------------------------
        */

        /*
        | Gallery image deletion must be available in addition
        | to normal Gallery CRUD.
        */

        Route::delete(
            '/gallery/{gallery}/images/{image}',
            [
                GalleryController::class,
                'destroyImage',
            ]
        )->name(
            'gallery.images.destroy'
        );


        Route::resource(
            'gallery',
            GalleryController::class
        );


        /*
        |--------------------------------------------------------------------------
        | Announcements
        |--------------------------------------------------------------------------
        */

        Route::resource(
            'announcements',
            AnnouncementController::class
        );


        /*
        |--------------------------------------------------------------------------
        | Events
        |--------------------------------------------------------------------------
        */

        Route::resource(
            'events',
            EventController::class
        );


        /*
        |--------------------------------------------------------------------------
        | Alumni
        |--------------------------------------------------------------------------
        |
        | Add
        | Edit
        | Approve
        | Reject/Unpublish
        | Delete
        |
        */

        Route::resource(
            'alumni',
            AlumniController::class
        )->except([
            'show',
        ]);

        Route::resource('alumni', AlumniController::class)->except(['admin.alumni.show']);

        Route::patch(
            '/alumni/{alumnus}/approve',
            [
                AlumniController::class,
                'approve',
            ]
        )->name(
            'alumni.approve'
        );


        Route::patch(
            '/alumni/{alumnus}/reject',
            [
                AlumniController::class,
                'reject',
            ]
        )->name(
            'alumni.reject'
        );


        /*
        |--------------------------------------------------------------------------
        | FINANCE
        |--------------------------------------------------------------------------
        */


        /*
        |--------------------------------------------------------------------------
        | Bank Accounts - VIEW ONLY
        |--------------------------------------------------------------------------
        |
        | No create/store/edit/update/delete routes.
        |
        */

        Route::get(
            '/bank-accounts',
            [
                BankAccountController::class,
                'index',
            ]
        )->name(
            'bank-accounts.index'
        );


        Route::get(
            '/bank-accounts/{bankAccount}',
            [
                BankAccountController::class,
                'show',
            ]
        )->name(
            'bank-accounts.show'
        );


        Route::get(
            '/bank-accounts/{bankAccount}/print',
            [
                BankAccountController::class,
                'print',
            ]
        )->name(
            'bank-accounts.print'
        );


        /*
        |--------------------------------------------------------------------------
        | Contact Messages
        |--------------------------------------------------------------------------
        */

        Route::get('/contact-messages', [
            ContactMessageController::class,
            'index',
        ])->name('contact-messages.index');

        Route::get('/contact-messages/{contactMessage}', [
            ContactMessageController::class,
            'show',
        ])->name('contact-messages.show');

        Route::patch('/contact-messages/{contactMessage}/read', [
            ContactMessageController::class,
            'markRead',
        ])->name('contact-messages.read');

        Route::patch('/contact-messages/{contactMessage}/unread', [
            ContactMessageController::class,
            'markUnread',
        ])->name('contact-messages.unread');

        Route::patch('/contact-messages/{contactMessage}/notes', [
            ContactMessageController::class,
            'updateNotes',
        ])->name('contact-messages.notes');

        Route::delete('/contact-messages/{contactMessage}', [
            ContactMessageController::class,
            'destroy',
        ])->name('contact-messages.destroy');


        /*
        |--------------------------------------------------------------------------
        | Profile / Account
        |--------------------------------------------------------------------------
        */

        /*
        | No profile routes here because they remain outside
        | the /admin prefix below.
        */

    });


/*
|--------------------------------------------------------------------------
| PROFILE
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'active.user'])->group(function () {


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