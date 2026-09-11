<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admission;
use App\Models\AdmissionSession;
use App\Models\BankAccount;
use App\Models\Course;
use App\Models\FeePayment;
use App\Models\Voucher;

class DashboardController extends Controller
{
    public function index()
    {
        $applicationCount = Admission::count();

        $studentCount = Admission::where(
            'admission_status',
            'admitted'
        )->count();

        $courseCount = Course::where(
            'status',
            true
        )->count();

        $bankAccountCount = BankAccount::where(
            'status',
            true
        )->count();

        $pendingPayments = FeePayment::where(
            'status',
            'pending'
        )->count();

        $pendingVouchers = Voucher::whereIn(
            'status',
            ['generated', 'submitted']
        )->count();

        $feeCollection = FeePayment::where(
            'status',
            'verified'
        )->sum('amount');

        $activeSession = AdmissionSession::where(
            'is_open',
            true
        )
            ->where('opening_date', '<=', now())
            ->where('closing_date', '>=', now())
            ->latest('opening_date')
            ->first();

        $activeSessionCount = $activeSession ? 1 : 0;

        return view('admin.dashboard', compact(
            'applicationCount',
            'studentCount',
            'courseCount',
            'feeCollection',
            'pendingPayments',
            'pendingVouchers',
            'bankAccountCount',
            'activeSession',
            'activeSessionCount'
        ));
    }
}