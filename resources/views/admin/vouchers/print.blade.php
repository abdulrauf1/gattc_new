@php
    /*
    |--------------------------------------------------------------------------
    | Copies (same order as cbt.pdf: Student | Institute/Centre | Bank)
    |--------------------------------------------------------------------------
    */
    $copies = [
        "Student's Copy",
        'Institute/Centre Copy',
        "Bank 's Copy",
    ];

    /*
    |--------------------------------------------------------------------------
    | Fee heading
    |--------------------------------------------------------------------------
    */
    $feeHeading = match ($voucher->voucher_type) {
        'hostel'      => 'Hostel Fee',
        'readmission' => 'Re-Admission Fee',
        default       => 'Admission Fee',
    };

    /*
    |--------------------------------------------------------------------------
    | Bank / account (fallback to the values printed on the official form)
    |--------------------------------------------------------------------------
    */
    $accountTitle = $voucher->account_title ?: 'PRINCIPAL GOVT ADV TECH TRG CENT';
    $iban         = $voucher->iban          ?: 'PK35 KHYB 0101 0030 0092 4945';

    /*
    |--------------------------------------------------------------------------
    | Student / course data
    |--------------------------------------------------------------------------
    */
    $courseTitle = $voucher->course?->title
        ?: ($voucher->voucher_type === 'hostel' ? 'Hostel' : '');

    $duration = $voucher->course?->duration ?: '';
    $session  = $voucher->session?->title   ?: '';

    $admissionNo = $voucher->admission?->admission_no ?? '';

    // Shift (Morning / Evening) – adjust the source if your column is named differently
    $shift     = strtolower((string) (data_get($voucher, 'admission.shift') ?? data_get($voucher, 'shift') ?? ''));
    $isMorning = str_contains($shift, 'morn');
    $isEvening = str_contains($shift, 'even');

    // Qualification type (CBT / Traditional)
    $qual   = strtolower((string) ($voucher->course?->course_type ?? ''));
    $isCbt  = str_contains($qual, 'cbt');
    $isTrad = str_contains($qual, 'trad');

    /*
    |--------------------------------------------------------------------------
    | Details of deposit – amount is printed on the matching line
    |--------------------------------------------------------------------------
    */
    $items = [
        1 => 'Admission Fee',
        2 => 'Tuition Fee',
        3 => 'Board Registration Fee',
        4 => 'Certificate Fee',
        5 => 'Examination Fee',
        6 => 'Fine/Struck Off, If any',
        7 => 'Identity Card Fee',
        8 => 'Miscellaneous Charges',
        9 => 'Re-Admission Fee',
    ];

    $activeLine = match ($voucher->voucher_type) {
        'admission'   => 1,
        'readmission' => 9,
        default       => 8,   // hostel / anything else -> Miscellaneous
    };

    $amount = number_format($voucher->amount, 2);

    /*
    |--------------------------------------------------------------------------
    | Images (all optional – place the files in public/images/)
    |--------------------------------------------------------------------------
    */
    $logoLeft  = file_exists(public_path('images/kp-logo.png')) ? asset('images/kp-logo.png') : null;
    $logoRight = file_exists(public_path('images/tevta-logo.png')) ? asset('images/tevta-logo.png') : null;
    $bokMark   = file_exists(public_path('images/bok-logo.png')) ? asset('images/bok-logo.png') : null;
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>{{ $voucher->voucher_no }} - Challan</title>

    <style>
        @page {
            size: A4 landscape;
            margin: 5mm;
        }

        * {
            box-sizing: border-box;
        }

        html, body {
            margin: 0;
            padding: 0;
            background: #fff;
            color: #000;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        body {
            font-family: "Courier New", Courier, monospace;
            font-size: 8.5px;
        }

        /* ---------- Page layout ---------- */
        .page {
            display: flex;
            align-items: stretch;
            width: 100%;
            height: 199mm;
        }

        .copy {
            flex: 1 1 0;
            min-width: 0;
            border: 1.5px solid #000;
            display: flex;
            flex-direction: column;
            position: relative;
            overflow: hidden;
            background: #fff;
        }

        /* scissor / dashed cut line between copies */
        .cut {
            flex: 0 0 6mm;
            position: relative;
        }

        .cut::before {
            content: "";
            position: absolute;
            top: 6mm;
            bottom: 0;
            left: 50%;
            border-left: 1px dashed #444;
        }

        .cut span {
            position: absolute;
            top: 0;
            left: 50%;
            transform: translateX(-50%);
            font-size: 13px;
            line-height: 1;
        }

        /* ---------- Watermark ---------- */
        .watermark {
            position: absolute;
            left: 50%;
            top: 47%;
            transform: translate(-50%, -50%);
            width: 55%;
            opacity: 0.18;
            z-index: 0;
            pointer-events: none;
            text-align: center;
        }

        .watermark img {
            width: 100%;
            display: block;
        }

        .watermark .wm-text {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 18px;
            color: #6b7f99;
        }

        .copy > *:not(.watermark) {
            position: relative;
            z-index: 1;
        }

        /* ---------- Title strip ---------- */
        .copy-title {
            flex: 0 0 auto;
            text-align: center;
            font-family: "Times New Roman", Times, serif;
            font-weight: 700;
            font-size: 7.5px;
            padding: 1.3mm 1mm;
            border-bottom: 1px solid #000;
        }

        /* ---------- Authority header ---------- */
        .authority {
            flex: 0 0 auto;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1mm;
            padding: 1.5mm 2mm;
            border-bottom: 1px solid #000;
            height: 17mm;
        }

        .authority .logo {
            flex: 0 0 13mm;
            width: 13mm;
            height: 13mm;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .authority .logo img {
            max-width: 100%;
            max-height: 100%;
            object-fit: contain;
        }

        .authority .name {
            flex: 1;
            text-align: center;
            font-family: "Times New Roman", Times, serif;
            font-weight: 700;
            font-size: 9px;
            line-height: 1.25;
            text-transform: uppercase;
        }

        /* ---------- Field rows ---------- */
        .row {
            flex: 0 0 auto;
            height: 6.9mm;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 2mm;
            padding: 0 1.5mm;
            border-bottom: 1px solid #000;
            white-space: nowrap;
            overflow: hidden;
        }

        .row.tall {
            height: 10.5mm;
            flex-direction: column;
            align-items: flex-start;
            justify-content: center;
            gap: 0.4mm;
            white-space: normal;
            border-bottom: 3px double #000;
        }

        .row .lbl {
            flex: 0 0 auto;
        }

        .row .val {
            font-family: Arial, Helvetica, sans-serif;
            font-weight: 700;
            font-size: 7.5px;
            margin-left: 1mm;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .row .grow {
            flex: 1 1 auto;
            min-width: 0;
            display: flex;
            align-items: center;
            overflow: hidden;
        }

        .row .right {
            flex: 0 0 auto;
            display: flex;
            align-items: center;
        }

        /* Pure-CSS tick box */
        .box {
            display: inline-block;
            width: 2.7mm;
            height: 2.7mm;
            border: 1px solid #000;
            margin: 0 2mm 0 1mm;
            vertical-align: middle;
            position: relative;
            flex: 0 0 auto;
        }

        .box.on::after {
            content: "\2713";
            position: absolute;
            left: 0;
            top: -1.2mm;
            font-size: 11px;
            font-weight: 700;
            line-height: 1;
        }

        /* ---------- Details of deposit ---------- */
        .details-title {
            flex: 0 0 auto;
            text-align: center;
            font-weight: 700;
            font-size: 10px;
            padding: 1.6mm 0 1.2mm;
        }

        .items {
            flex: 1 1 auto;
            display: flex;
            flex-direction: column;
            justify-content: space-evenly;
            padding: 0 3mm 0 2.5mm;
        }

        .item {
            display: flex;
            align-items: baseline;
            font-size: 8.5px;
        }

        .item .no {
            flex: 0 0 4mm;
        }

        .item .desc {
            flex: 1 1 auto;
            white-space: nowrap;
        }

        .item .rs {
            flex: 0 0 auto;
            margin-right: 1mm;
        }

        .item .fill {
            flex: 0 0 22mm;
            border-bottom: 1px solid #000;
            text-align: right;
            font-family: Arial, Helvetica, sans-serif;
            font-weight: 700;
            font-size: 8px;
            min-height: 3.2mm;
            padding-right: 0.5mm;
        }

        .total {
            flex: 0 0 auto;
            display: flex;
            align-items: baseline;
            padding: 1mm 3mm 0 4.5mm;
            margin-top: 1.5mm;
            font-weight: 700;
            font-size: 9px;
        }

        .total .desc {
            flex: 1 1 auto;
        }

        .total .rs {
            margin-right: 1mm;
        }

        .total .fill {
            flex: 0 0 22mm;
            border-bottom: 1px solid #000;
            text-align: right;
            font-family: Arial, Helvetica, sans-serif;
            font-size: 8.5px;
            min-height: 3.4mm;
            padding-right: 0.5mm;
        }

        /* ---------- Signatures ---------- */
        .signs {
            flex: 0 0 auto;
            display: flex;
            justify-content: space-between;
            gap: 6mm;
            padding: 6mm 3mm 2mm;
        }

        .sign {
            flex: 1;
            border-top: 1px solid #000;
            padding-top: 0.8mm;
            text-align: center;
            font-size: 6px;
            line-height: 1.25;
        }

        /* ---------- Screen-only toolbar ---------- */
        @media screen {
            body {
                background: #e5e7eb;
                padding: 10px;
            }

            .print-bar {
                max-width: 1120px;
                margin: 0 auto 10px;
                padding: 8px 10px;
                background: #fff;
                border: 1px solid #d1d5db;
                display: flex;
                justify-content: space-between;
                align-items: center;
                font-family: Arial, Helvetica, sans-serif;
                font-size: 13px;
            }

            .print-button {
                border: 0;
                background: #059669;
                color: #fff;
                border-radius: 6px;
                padding: 8px 14px;
                cursor: pointer;
                font-weight: 700;
            }

            .page {
                max-width: 1120px;
                margin: 0 auto;
                background: #fff;
                padding: 5px;
                height: 760px;
            }
        }

        @media print {
            .print-bar {
                display: none;
            }
        }
    </style>
</head>

<body>

<div class="print-bar">
    <div>
        <strong>{{ $voucher->voucher_no }}</strong>
        <span style="margin-left:10px;color:#6b7280;">{{ $voucher->voucher_type_label }}</span>
    </div>

    <button type="button" class="print-button" onclick="window.print()">Print Challan</button>
</div>

<div class="page">

@foreach($copies as $copyTitle)

    <div class="copy">

        {{-- Watermark --}}
        <div class="watermark">
            @if($bokMark)
                <img src="{{ $bokMark }}" alt="Bank of Khyber">
            @else
                <div class="wm-text">Bank of Khyber</div>
            @endif
        </div>

        {{-- Title strip --}}
        <div class="copy-title">
            {{ $feeHeading }} Challan Receipt ({{ $copyTitle }})
        </div>

        {{-- Authority header --}}
        <div class="authority">
            <div class="logo">
                @if($logoLeft)
                    <img src="{{ $logoLeft }}" alt="KP Govt">
                @endif
            </div>

            <div class="name">
                Khyber Pakhtunkhwa<br>
                Technical Education &amp; Vocational<br>
                Training Authority
            </div>

            <div class="logo">
                @if($logoRight)
                    <img src="{{ $logoRight }}" alt="KP-TEVTA">
                @endif
            </div>
        </div>

        {{-- Receipt No / Dated --}}
        <div class="row">
            <div class="grow">
                <span class="lbl">Receipt No:</span>
                <span class="val">{{ $voucher->voucher_no }}</span>
            </div>
            <div class="right">
                <span class="lbl">Dated:</span>
                <span class="val">{{ optional($voucher->issue_date)->format('d-m-Y') }}</span>
            </div>
        </div>

        {{-- Admission No --}}
        <div class="row">
            <div class="grow">
                <span class="lbl">Admission No:</span>
                <span class="val">{{ $admissionNo }}</span>
            </div>
        </div>

        {{-- Account Title --}}
        <div class="row">
            <div class="grow">
                <span class="lbl">Account Title:</span>
                <span class="val">{{ $accountTitle }}</span>
            </div>
        </div>

        {{-- IBAN --}}
        <div class="row">
            <div class="grow">
                <span class="lbl">IBAN:</span>
                <span class="val" style="margin-left:3mm;">{{ $iban }}</span>
            </div>
        </div>

        {{-- Shift / Session --}}
        <div class="row">
            <div class="grow">
                <span class="lbl">Shift: Morning</span><span class="box {{ $isMorning ? 'on' : '' }}"></span>
                <span class="lbl">Evening</span><span class="box {{ $isEvening ? 'on' : '' }}"></span>
            </div>
            <div class="right">
                <span class="lbl">Session:</span>
                <span class="val">{{ $session }}</span>
            </div>
        </div>

        {{-- Institute --}}
        <div class="row">
            <div class="grow">
                <span class="lbl">Name of Institute:</span>
                <span class="val">GATTC Hayatabad Peshawar</span>
            </div>
        </div>

        {{-- Student --}}
        <div class="row">
            <div class="grow">
                <span class="lbl">Name of Student:</span>
                <span class="val">{{ $voucher->applicant_name }}</span>
            </div>
        </div>

        <div class="row">
            <div class="grow">
                <span class="lbl">CNIC/FORM-B:</span>
                <span class="val">{{ $voucher->cnic }}</span>
            </div>
        </div>

        <div class="row">
            <div class="grow">
                <span class="lbl">Contact No:</span>
                <span class="val">{{ $voucher->phone }}</span>
            </div>
        </div>

        <div class="row">
            <div class="grow">
                <span class="lbl">Father's Name:</span>
                <span class="val">{{ $voucher->father_name }}</span>
            </div>
        </div>

        {{-- Trade / Class --}}
        <div class="row">
            <div class="grow">
                <span class="lbl">Trade/Technology:</span>
                <span class="val">{{ $courseTitle }}</span>
            </div>
            <div class="right">
                <span class="lbl">Class No:</span>
            </div>
        </div>

        {{-- Institute type --}}
        <div class="row">
            <div class="grow">
                <span class="lbl">Institute Type:</span>
                <span class="val">Vocational</span>
            </div>
        </div>

        {{-- Qualification type --}}
        <div class="row">
            <div class="grow">
                <span class="lbl">Qualification Type: CBT</span><span class="box {{ $isCbt ? 'on' : '' }}"></span>
                <span class="lbl">Traditional</span><span class="box {{ $isTrad ? 'on' : '' }}"></span>
            </div>
        </div>

        {{-- Duration --}}
        <div class="row tall">
            <span class="lbl">In case of Certificate/Short Course</span>
            <span>
                <span class="lbl">Duration of Course:</span>
                <span class="val">{{ (!$isCbt && !$isTrad) ? $duration : '' }}</span>
            </span>
        </div>

        @include(
    'admin.vouchers._deposit-details',
    [
        'depositDetails' => $depositDetails,
    ]
)

        {{-- Signatures --}}
        <div class="signs">
            <div class="sign">Depositor Sign</div>
            <div class="sign">Bank Officer<br>Stamp &amp; Sign</div>
        </div>

    </div>

    @unless($loop->last)
        <div class="cut"><span>&#9986;</span></div>
    @endunless

@endforeach

</div>

</body>
</html>