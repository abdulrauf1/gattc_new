@extends('layouts.admin')

@section('page-heading', 'Voucher Details')

@section('content')
@php
    $status = (string) ($voucher->status ?? 'generated');

    $statusMeta = match ($status) {
        'paid' => [
            'label' => 'Paid',
            'class' => 'bg-emerald-50 text-emerald-700 ring-emerald-200',
            'icon' => 'check-circle-2',
        ],
        'cancelled' => [
            'label' => 'Cancelled',
            'class' => 'bg-red-50 text-red-700 ring-red-200',
            'icon' => 'ban',
        ],
        default => [
            'label' => ucfirst($status ?: 'Generated'),
            'class' => 'bg-blue-50 text-blue-700 ring-blue-200',
            'icon' => 'clock-3',
        ],
    };

    $voucherTypeLabel = $voucher->voucher_type_label
        ?? ucfirst((string) ($voucher->voucher_type ?? 'Voucher'));

    $course = $voucher->course;
    $admission = $voucher->admission;
    $session = $voucher->session;

    $courseTitle = $course?->title
        ?: (($voucher->voucher_type ?? null) === 'hostel' ? 'Hostel' : '—');

    $courseTypeLabel = match ($course?->course_type) {
        'regular' => 'Regular',
        'dit' => 'DIT',
        'private' => 'Private / IMC',
        default => $course?->course_type ? ucfirst($course->course_type) : '—',
    };

    $formatDate = static function ($value): string {
        if (!$value) {
            return '—';
        }

        try {
            return \Illuminate\Support\Carbon::parse($value)->format('d M Y');
        } catch (\Throwable $exception) {
            return '—';
        }
    };

    $issuedDate = $formatDate($voucher->issue_date ?? null);
    $dueDate = $formatDate($voucher->due_date ?? null);
    $dateOfBirth = $formatDate($voucher->date_of_birth ?? null);
    $createdDate = $formatDate($voucher->created_at ?? null);

    $admissionStatusClass = match ($admission?->status) {
        'approved' => 'text-emerald-700 bg-emerald-50',
        'rejected' => 'text-red-700 bg-red-50',
        default => 'text-amber-700 bg-amber-50',
    };
@endphp

<div class="max-w-7xl mx-auto space-y-5">

    {{-- Page header --}}
    <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
        <div class="min-w-0">
            <div class="flex flex-wrap items-center gap-2">
                <h1 class="text-2xl font-bold tracking-tight text-gray-900">Voucher Details</h1>
                <span class="inline-flex items-center gap-1.5 rounded-full px-2.5 py-1 text-xs font-semibold ring-1 ring-inset {{ $statusMeta['class'] }}">
                    <i data-lucide="{{ $statusMeta['icon'] }}" class="h-3.5 w-3.5"></i>
                    {{ $statusMeta['label'] }}
                </span>
            </div>
            <div class="mt-1.5 flex flex-wrap items-center gap-x-2 gap-y-1 text-sm text-gray-500">
                <span>Voucher no.</span>
                <span id="voucher-number" class="font-semibold text-gray-800">{{ $voucher->voucher_no }}</span>
                <button type="button" id="copy-voucher-number" class="inline-flex items-center gap-1 rounded-md px-1.5 py-0.5 text-xs font-medium text-blue-700 hover:bg-blue-50" aria-label="Copy voucher number">
                    <i data-lucide="copy" class="h-3.5 w-3.5"></i>
                    <span>Copy</span>
                </button>
                <span class="hidden text-xs font-medium text-emerald-700" id="copy-feedback" role="status">Copied</span>
            </div>
            <p class="mt-1 text-sm text-gray-500">{{ $voucherTypeLabel }} voucher · Created {{ $createdDate }}</p>
        </div>

        <div class="flex flex-wrap items-center gap-2">
            <a href="{{ route('admin.vouchers.index') }}" class="inline-flex h-10 items-center justify-center gap-2 rounded-lg border border-gray-200 bg-white px-3.5 text-sm font-medium text-gray-700 shadow-sm transition hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                <i data-lucide="arrow-left" class="h-4 w-4"></i>
                Back to Vouchers
            </a>

            @if(\Illuminate\Support\Facades\Route::has('admin.vouchers.edit') && $status !== 'paid' && $status !== 'cancelled')
                <a href="{{ route('admin.vouchers.edit', $voucher) }}" class="inline-flex h-10 items-center justify-center gap-2 rounded-lg border border-gray-200 bg-white px-3.5 text-sm font-medium text-gray-700 shadow-sm transition hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                    <i data-lucide="pencil" class="h-4 w-4"></i>
                    Edit Voucher
                </a>
            @endif

            <a href="{{ route('admin.vouchers.print', $voucher) }}" target="_blank" rel="noopener noreferrer" class="inline-flex h-10 items-center justify-center gap-2 rounded-lg bg-emerald-600 px-4 text-sm font-semibold text-white shadow-sm transition hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2">
                <i data-lucide="printer" class="h-4 w-4"></i>
                Print Challan
            </a>
        </div>
    </div>

    {{-- Flash and validation messages --}}
    @if(session('success'))
        <div class="flex items-start gap-3 rounded-xl border border-emerald-200 bg-emerald-50 p-4 text-sm text-emerald-800" role="status">
            <i data-lucide="check-circle-2" class="mt-0.5 h-5 w-5 shrink-0"></i>
            <p>{{ session('success') }}</p>
        </div>
    @endif

    @if(session('error'))
        <div class="flex items-start gap-3 rounded-xl border border-red-200 bg-red-50 p-4 text-sm text-red-800" role="alert">
            <i data-lucide="alert-circle" class="mt-0.5 h-5 w-5 shrink-0"></i>
            <p>{{ session('error') }}</p>
        </div>
    @endif

    @if($errors->any())
        <div class="rounded-xl border border-red-200 bg-red-50 p-4 text-sm text-red-800" role="alert">
            <div class="flex items-start gap-3">
                <i data-lucide="alert-triangle" class="mt-0.5 h-5 w-5 shrink-0"></i>
                <div>
                    <p class="font-semibold">Please review the following:</p>
                    <ul class="mt-1 list-inside list-disc space-y-0.5">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    @endif

    {{-- Status notice --}}
    @if($status === 'paid')
        <div class="flex items-start gap-3 rounded-xl border border-emerald-200 bg-emerald-50 p-4">
            <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-emerald-100 text-emerald-700">
                <i data-lucide="badge-check" class="h-5 w-5"></i>
            </div>
            <div>
                <h2 class="text-sm font-semibold text-emerald-900">Payment recorded</h2>
                <p class="mt-1 text-sm text-emerald-800">This voucher is marked as paid. Keep the voucher and deposit record for reference.</p>
            </div>
        </div>
    @elseif($status === 'cancelled')
        <div class="flex items-start gap-3 rounded-xl border border-red-200 bg-red-50 p-4">
            <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-red-100 text-red-700">
                <i data-lucide="ban" class="h-5 w-5"></i>
            </div>
            <div>
                <h2 class="text-sm font-semibold text-red-900">Voucher cancelled</h2>
                <p class="mt-1 text-sm text-red-800">This voucher is cancelled and should not be used for a fee deposit.</p>
            </div>
        </div>
    @else
        <div class="flex items-start gap-3 rounded-xl border border-blue-200 bg-blue-50 p-4">
            <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-blue-100 text-blue-700">
                <i data-lucide="info" class="h-5 w-5"></i>
            </div>
            <div>
                <h2 class="text-sm font-semibold text-blue-900">Payment pending verification</h2>
                <p class="mt-1 text-sm text-blue-800">This voucher has been generated. Verify the physical bank deposit slip through the authorized administration process before treating the fee as paid.</p>
            </div>
        </div>
    @endif

    {{-- Summary cards --}}
    <div class="grid grid-cols-1 gap-3 sm:grid-cols-2 xl:grid-cols-4">
        <div class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm">
            <div class="flex items-center justify-between gap-3">
                <p class="text-xs font-semibold uppercase tracking-wide text-gray-500">Voucher Amount</p>
                <span class="flex h-9 w-9 items-center justify-center rounded-lg bg-emerald-50 text-emerald-700"><i data-lucide="wallet" class="h-4 w-4"></i></span>
            </div>
            <p class="mt-2 text-2xl font-bold tracking-tight text-gray-900">Rs. {{ number_format((float) $voucher->amount, 2) }}</p>
            <p class="mt-1 text-xs text-gray-500">Amount payable on this challan</p>
        </div>

        <div class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm">
            <div class="flex items-center justify-between gap-3">
                <p class="text-xs font-semibold uppercase tracking-wide text-gray-500">Applicant</p>
                <span class="flex h-9 w-9 items-center justify-center rounded-lg bg-blue-50 text-blue-700"><i data-lucide="user-round" class="h-4 w-4"></i></span>
            </div>
            <p class="mt-2 truncate text-base font-bold text-gray-900" title="{{ $voucher->applicant_name }}">{{ $voucher->applicant_name }}</p>
            <p class="mt-1 text-xs text-gray-500">CNIC: {{ $voucher->cnic ?: '—' }}</p>
        </div>

        <div class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm">
            <div class="flex items-center justify-between gap-3">
                <p class="text-xs font-semibold uppercase tracking-wide text-gray-500">Course / Fee</p>
                <span class="flex h-9 w-9 items-center justify-center rounded-lg bg-violet-50 text-violet-700"><i data-lucide="graduation-cap" class="h-4 w-4"></i></span>
            </div>
            <p class="mt-2 truncate text-base font-bold text-gray-900" title="{{ $courseTitle }}">{{ $courseTitle }}</p>
            <p class="mt-1 text-xs text-gray-500">{{ $courseTypeLabel }}</p>
        </div>

        <div class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm">
            <div class="flex items-center justify-between gap-3">
                <p class="text-xs font-semibold uppercase tracking-wide text-gray-500">Payment Due</p>
                <span class="flex h-9 w-9 items-center justify-center rounded-lg bg-amber-50 text-amber-700"><i data-lucide="calendar-clock" class="h-4 w-4"></i></span>
            </div>
            <p class="mt-2 text-base font-bold text-gray-900">{{ $dueDate }}</p>
            <p class="mt-1 text-xs text-gray-500">Issue date: {{ $issuedDate }}</p>
        </div>
    </div>

    {{-- Applicant information --}}
    <section class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">
        <div class="flex items-center gap-3 border-b border-gray-100 bg-gray-50/80 px-5 py-4">
            <span class="flex h-9 w-9 items-center justify-center rounded-lg bg-blue-100 text-blue-700"><i data-lucide="user-round" class="h-4 w-4"></i></span>
            <div>
                <h2 class="text-sm font-bold text-gray-900">Applicant Information</h2>
                <p class="mt-0.5 text-xs text-gray-500">Personal and contact details recorded on the voucher.</p>
            </div>
        </div>
        <div class="grid grid-cols-1 gap-x-6 gap-y-5 p-5 sm:grid-cols-2 xl:grid-cols-4">
            <div><p class="detail-label">Student Name</p><p class="detail-value">{{ $voucher->applicant_name ?: '—' }}</p></div>
            <div><p class="detail-label">Father Name</p><p class="detail-value">{{ $voucher->father_name ?: '—' }}</p></div>
            <div><p class="detail-label">CNIC / Form-B</p><p class="detail-value">{{ $voucher->cnic ?: '—' }}</p></div>
            <div><p class="detail-label">Contact Number</p><p class="detail-value">{{ $voucher->phone ?: '—' }}</p></div>
            <div><p class="detail-label">Date of Birth</p><p class="detail-value">{{ $dateOfBirth }}</p></div>
            <div><p class="detail-label">Gender</p><p class="detail-value">{{ $voucher->gender ? ucfirst($voucher->gender) : '—' }}</p></div>
            <div class="sm:col-span-2"><p class="detail-label">Email Address</p><p class="detail-value break-words">{{ $voucher->email ?: '—' }}</p></div>
            <div class="sm:col-span-2 xl:col-span-4"><p class="detail-label">Residential Address</p><p class="detail-value whitespace-pre-line break-words">{{ $voucher->address ?: '—' }}</p></div>
        </div>
    </section>

    {{-- Academic and admission information --}}
    <section class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">
        <div class="flex items-center gap-3 border-b border-gray-100 bg-gray-50/80 px-5 py-4">
            <span class="flex h-9 w-9 items-center justify-center rounded-lg bg-violet-100 text-violet-700"><i data-lucide="book-open" class="h-4 w-4"></i></span>
            <div>
                <h2 class="text-sm font-bold text-gray-900">Academic &amp; Admission Information</h2>
                <p class="mt-0.5 text-xs text-gray-500">Voucher category, course, session and linked admission.</p>
            </div>
        </div>
        <div class="grid grid-cols-1 gap-x-6 gap-y-5 p-5 sm:grid-cols-2 xl:grid-cols-4">
            <div><p class="detail-label">Voucher Type</p><p class="detail-value">{{ $voucherTypeLabel }}</p></div>
            <div><p class="detail-label">Course</p><p class="detail-value">{{ $courseTitle }}</p></div>
            <div><p class="detail-label">Course Type</p><p class="detail-value">{{ $courseTypeLabel }}</p></div>
            <div><p class="detail-label">Admission Session</p><p class="detail-value">{{ $session?->title ?: '—' }}</p></div>
            @if($course?->duration)
                <div><p class="detail-label">Course Duration</p><p class="detail-value">{{ $course->duration }}</p></div>
            @endif
            @if($admission)
                <div><p class="detail-label">Admission Number</p><p class="detail-value font-semibold">{{ $admission->admission_no ?: '—' }}</p></div>
                <div><p class="detail-label">Admission Status</p><p class="mt-1"><span class="inline-flex rounded-full px-2.5 py-1 text-xs font-semibold {{ $admissionStatusClass }}">{{ ucfirst((string) ($admission->status ?: 'Pending')) }}</span></p></div>
            @endif
        </div>
    </section>

    {{-- Bank information --}}
    <section class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">
        <div class="flex flex-col gap-2 border-b border-gray-100 bg-gray-50/80 px-5 py-4 sm:flex-row sm:items-center sm:justify-between">
            <div class="flex items-center gap-3">
                <span class="flex h-9 w-9 items-center justify-center rounded-lg bg-emerald-100 text-emerald-700"><i data-lucide="landmark" class="h-4 w-4"></i></span>
                <div>
                    <h2 class="text-sm font-bold text-gray-900">Receiving Bank Account</h2>
                    <p class="mt-0.5 text-xs text-gray-500">Account snapshot saved when this voucher was generated.</p>
                </div>
            </div>
            @if($course?->course_type === 'private')
                <span class="inline-flex w-fit items-center rounded-full bg-indigo-50 px-2.5 py-1 text-xs font-semibold text-indigo-700">PRIVATE / IMC</span>
            @endif
        </div>
        <div class="grid grid-cols-1 gap-x-6 gap-y-5 p-5 sm:grid-cols-2 xl:grid-cols-4">
            <div><p class="detail-label">Bank Name</p><p class="detail-value">{{ $voucher->bank_name ?: '—' }}</p></div>
            <div class="sm:col-span-2"><p class="detail-label">Account Title</p><p class="detail-value">{{ $voucher->account_title ?: '—' }}</p></div>
            <div><p class="detail-label">Account Number</p><p class="detail-value break-all">{{ $voucher->account_number ?: '—' }}</p></div>
            <div class="sm:col-span-2"><p class="detail-label">IBAN</p><p class="detail-value break-all">{{ $voucher->iban ?: '—' }}</p></div>
            <div><p class="detail-label">Branch</p><p class="detail-value">{{ $voucher->branch_name ?: '—' }}</p></div>
            <div><p class="detail-label">Branch Code</p><p class="detail-value">{{ $voucher->branch_code ?: '—' }}</p></div>
        </div>
    </section>

    {{-- Voucher dates and remarks --}}
    <section class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">
        <div class="flex items-center gap-3 border-b border-gray-100 bg-gray-50/80 px-5 py-4">
            <span class="flex h-9 w-9 items-center justify-center rounded-lg bg-amber-100 text-amber-700"><i data-lucide="calendar-days" class="h-4 w-4"></i></span>
            <div>
                <h2 class="text-sm font-bold text-gray-900">Voucher Dates &amp; Remarks</h2>
                <p class="mt-0.5 text-xs text-gray-500">Issuance details and any administrative notes.</p>
            </div>
        </div>
        <div class="grid grid-cols-1 gap-5 p-5 sm:grid-cols-3">
            <div><p class="detail-label">Issue Date</p><p class="detail-value">{{ $issuedDate }}</p></div>
            <div><p class="detail-label">Due Date</p><p class="detail-value">{{ $dueDate }}</p></div>
            <div><p class="detail-label">Voucher Amount</p><p class="mt-1 text-xl font-bold tracking-tight text-gray-900">Rs. {{ number_format((float) $voucher->amount, 2) }}</p></div>
        </div>
        @if($voucher->remarks)
            <div class="border-t border-gray-100 bg-gray-50/70 px-5 py-4">
                <p class="detail-label">Administrative Remarks</p>
                <p class="mt-1.5 whitespace-pre-line break-words text-sm leading-6 text-gray-700">{{ $voucher->remarks }}</p>
            </div>
        @endif
    </section>

    {{-- Bottom actions for convenient access --}}
    <div class="flex flex-col-reverse gap-2 pb-2 sm:flex-row sm:items-center sm:justify-between">
        <p class="text-xs text-gray-500">For financial records, the saved bank account details reflect the snapshot stored on this voucher.</p>
        <a href="{{ route('admin.vouchers.print', $voucher) }}" target="_blank" rel="noopener noreferrer" class="inline-flex h-10 items-center justify-center gap-2 rounded-lg bg-emerald-600 px-4 text-sm font-semibold text-white shadow-sm transition hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2">
            <i data-lucide="printer" class="h-4 w-4"></i>
            Print Challan
        </a>
    </div>
</div>
@endsection

@push('styles')
<style>
    .detail-label {
        color: #6b7280;
        font-size: .68rem;
        font-weight: 700;
        letter-spacing: .055em;
        line-height: 1rem;
        text-transform: uppercase;
    }

    .detail-value {
        color: #1f2937;
        font-size: .875rem;
        font-weight: 500;
        line-height: 1.4rem;
        margin-top: .3rem;
    }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    if (window.lucide) {
        window.lucide.createIcons();
    }

    const copyButton = document.getElementById('copy-voucher-number');
    const voucherNumber = document.getElementById('voucher-number');
    const copyFeedback = document.getElementById('copy-feedback');

    if (copyButton && voucherNumber) {
        copyButton.addEventListener('click', async function () {
            const value = voucherNumber.textContent.trim();

            try {
                if (navigator.clipboard && window.isSecureContext) {
                    await navigator.clipboard.writeText(value);
                } else {
                    const temporaryInput = document.createElement('textarea');
                    temporaryInput.value = value;
                    temporaryInput.setAttribute('readonly', '');
                    temporaryInput.style.position = 'absolute';
                    temporaryInput.style.left = '-9999px';
                    document.body.appendChild(temporaryInput);
                    temporaryInput.select();
                    const copied = document.execCommand('copy');
                    temporaryInput.remove();
                    if (!copied) throw new Error('Copy failed');
                }

                if (copyFeedback) copyFeedback.classList.remove('hidden');
                copyButton.querySelector('span').textContent = 'Copied';
            } catch (error) {
                if (copyFeedback) {
                    copyFeedback.textContent = 'Copy failed — select the voucher number manually.';
                    copyFeedback.classList.remove('hidden');
                    copyFeedback.classList.remove('text-emerald-700');
                    copyFeedback.classList.add('text-red-700');
                }
            }
        });
    }
});
</script>
@endpush
