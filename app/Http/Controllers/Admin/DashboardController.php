<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admission;
use App\Models\AdmissionSession;
use App\Models\BankAccount;
use App\Models\Course;
use App\Models\FeePayment;
use App\Models\Voucher;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        /*
        |--------------------------------------------------------------------------
        | Admissions
        |--------------------------------------------------------------------------
        */

        $applicationCount = Admission::count();

        $studentCount = Admission::where(
            'status',
            'approved'
        )->count();

        $pendingApplications = Admission::where(
            'status',
            'pending'
        )->count();

        $rejectedApplications = Admission::where(
            'status',
            'rejected'
        )->count();


        /*
        |--------------------------------------------------------------------------
        | Courses
        |--------------------------------------------------------------------------
        */

        $courseCount = Course::where(
            'status',
            true
        )->count();


        /*
        |--------------------------------------------------------------------------
        | Bank Accounts
        |--------------------------------------------------------------------------
        */

        $bankAccountCount = BankAccount::where(
            'status',
            true
        )->count();


        /*
        |--------------------------------------------------------------------------
        | Fee Payments
        |--------------------------------------------------------------------------
        */

        $pendingPayments = FeePayment::where(
            'status',
            'pending'
        )->count();

        $approvedPayments = FeePayment::where(
            'status',
            'approved'
        )->count();

        $rejectedPayments = FeePayment::where(
            'status',
            'rejected'
        )->count();


        /*
        |--------------------------------------------------------------------------
        | Vouchers
        |--------------------------------------------------------------------------
        */

        $pendingVouchers = Voucher::where(
            'status',
            'generated'
        )->count();

        $paidVouchers = Voucher::where(
            'status',
            'paid'
        )->count();

        $cancelledVouchers = Voucher::where(
            'status',
            'cancelled'
        )->count();


        /*
        |--------------------------------------------------------------------------
        | Fee Collection
        |--------------------------------------------------------------------------
        |
        | FeePayment no longer has an "amount" column.
        | The amount belongs to the related voucher.
        |
        | Therefore calculate collection from:
        |
        | approved payment -> voucher -> amount
        |
        */
        $feeCollection = FeePayment::query()
            ->where('fee_payments.status', 'approved')
            ->join(
                'vouchers',
                'vouchers.id',
                '=',
                'fee_payments.voucher_id'
            )
            ->sum('vouchers.amount');


        /*
        |--------------------------------------------------------------------------
        | Active Admission Session
        |--------------------------------------------------------------------------
        */

        $activeSession = AdmissionSession::query()
            ->where('is_open', true)
            ->where('opening_date', '<=', now())
            ->where('closing_date', '>=', now())
            ->latest('opening_date')
            ->first();

        $activeSessionCount = $activeSession ? 1 : 0;


        /*
        |--------------------------------------------------------------------------
        | Recent Applications
        |--------------------------------------------------------------------------
        */

        $recentApplications = Admission::with([
                'course',
                'session',
            ])
            ->latest()
            ->take(5)
            ->get();


        /*
        |--------------------------------------------------------------------------
        | Recent Vouchers
        |--------------------------------------------------------------------------
        */

        $recentVouchers = Voucher::with([
                'course',
                'bankAccount',
                'session',
            ])
            ->latest()
            ->take(5)
            ->get();


        /*
        |--------------------------------------------------------------------------
        | Recent Payments
        |--------------------------------------------------------------------------
        */

        $recentPayments = FeePayment::with([
                'voucher.course',
                'voucher.bankAccount',
            ])
            ->latest()
            ->take(5)
            ->get();


        /*
        |--------------------------------------------------------------------------
        | Course Statistics
        |--------------------------------------------------------------------------
        */

        $courseStatistics = Course::query()
            ->select(
                'courses.id',
                'courses.title',
                'courses.course_type',
                'courses.fee_amount'
            )
            ->withCount('admissions')
            ->orderByDesc('admissions_count')
            ->take(5)
            ->get();


        /*
        |--------------------------------------------------------------------------
        | Monthly Collection
        |--------------------------------------------------------------------------
        |
        | Collection is calculated through vouchers.amount.
        |
        */
        $monthlyCollection = FeePayment::query()
            ->join(
                'vouchers',
                'vouchers.id',
                '=',
                'fee_payments.voucher_id'
            )
            ->where('fee_payments.status', 'approved')
            ->whereYear(
                'fee_payments.payment_date',
                now()->year
            )
            ->select(
                DB::raw('MONTH(fee_payments.payment_date) as month'),
                DB::raw('SUM(vouchers.amount) as total')
            )
            ->groupBy(
                DB::raw('MONTH(fee_payments.payment_date)')
            )
            ->orderBy('month')
            ->get();


        return view(
            'admin.dashboard',
            compact(
                'applicationCount',
                'studentCount',
                'pendingApplications',
                'rejectedApplications',

                'courseCount',

                'bankAccountCount',

                'pendingPayments',
                'approvedPayments',
                'rejectedPayments',

                'pendingVouchers',
                'paidVouchers',
                'cancelledVouchers',

                'feeCollection',

                'activeSession',
                'activeSessionCount',

                'recentApplications',
                'recentVouchers',
                'recentPayments',

                'courseStatistics',
                'monthlyCollection'
            )
        );
    }
}