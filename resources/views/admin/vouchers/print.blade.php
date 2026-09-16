@php
    $copies = [
        [
            'title' => "Bank's Copy",
            'sub'   => 'For deposit at Bank of Khyber',
        ],
        [
            'title' => 'GATTC Office Copy',
            'sub'   => 'For GATTC record',
        ],
        [
            'title' => "Applicant's Copy",
            'sub'   => 'For student record',
        ],
    ];

    $courseTitle =
        $voucher->course?->title
        ?: (
            $voucher->voucher_type === 'hostel'
                ? 'Hostel Fee'
                : '—'
        );

    $courseType =
        $voucher->course
            ? ucfirst($voucher->course->course_type)
            : ucfirst($voucher->voucher_type);

    $duration =
        $voucher->course?->duration
        ?: '—';

    $session =
        $voucher->session?->title
        ?: '—';

    $institute =
        'Government Advance Technical Training Centre';

    $feeLabel = match($voucher->voucher_type) {
        'admission' => 'Admission Fee',
        'hostel' => 'Hostel Fee',
        'readmission' => 'Re-Admission Fee',
        default => 'Fee',
    };
@endphp


<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <title>
        {{ $voucher->voucher_no }} - Challan
    </title>

    <style>

        @page {
            size: A4 landscape;
            margin: 5mm;
        }

        * {
            box-sizing: border-box;
        }

        html,
        body {
            margin: 0;
            padding: 0;
            background: #ffffff;
            font-family: Arial, Helvetica, sans-serif;
            color: #111827;
        }

        body {
            font-size: 8px;
        }

        .page {
            width: 100%;
            min-height: 190mm;
            display: grid;
            grid-template-columns:
                repeat(3, 1fr);
            gap: 3mm;
        }

        .copy {
            border: 1px solid #222;
            position: relative;
            min-width: 0;
            height: 188mm;
            overflow: hidden;
        }

        .copy:not(:last-child)::after {
            content: "✂";
            position: absolute;
            right: -2.9mm;
            top: 50%;
            transform: translateY(-50%);
            background: #fff;
            font-size: 12px;
            z-index: 10;
        }

        .copy-header {
            height: 15mm;
            border-bottom: 1px solid #222;
            text-align: center;
            padding: 2mm 1.5mm 1mm;
        }

        .copy-title {
            font-size: 7.5px;
            font-weight: 700;
            margin-bottom: 1.5mm;
            text-transform: uppercase;
        }

        .brand {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 2mm;
        }

        .brand-logo {
            width: 10mm;
            height: 10mm;
            object-fit: contain;
        }

        .brand-name {
            font-size: 9px;
            font-weight: 700;
            line-height: 1.2;
        }

        .brand-address {
            margin-top: 0.8mm;
            font-size: 6.5px;
        }

        .meta {
            display: grid;
            grid-template-columns: 1fr 1fr;
            border-bottom: 1px solid #222;
        }

        .meta-cell {
            padding: 1.3mm 1.5mm;
            border-right: 1px solid #222;
            min-height: 7mm;
        }

        .meta-cell:nth-child(2n) {
            border-right: none;
        }

        .meta-cell.full {
            grid-column: 1 / -1;
            border-right: none;
        }

        .label {
            display: block;
            font-size: 6px;
            font-weight: 700;
            color: #555;
            text-transform: uppercase;
            margin-bottom: 0.5mm;
        }

        .value {
            font-size: 7px;
            font-weight: 600;
            line-height: 1.25;
            word-break: break-word;
        }

        .bank-box {
            border-bottom: 1px solid #222;
            padding: 1.5mm;
        }

        .bank-name {
            font-size: 7px;
            font-weight: 700;
        }

        .bank-account {
            margin-top: 0.8mm;
            font-size: 8px;
            font-weight: 700;
        }

        .info-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            border-bottom: 1px solid #222;
        }

        .info-row .cell {
            padding: 1.2mm 1.5mm;
            border-right: 1px solid #222;
            min-height: 8mm;
        }

        .info-row .cell:last-child {
            border-right: none;
        }

        .single-row {
            display: grid;
            grid-template-columns: 1fr;
            border-bottom: 1px solid #222;
        }

        .single-row .cell {
            padding: 1.2mm 1.5mm;
            min-height: 7mm;
        }

        .section-title {
            text-align: center;
            font-size: 7px;
            font-weight: 700;
            text-decoration: underline;
            padding: 1.2mm 1mm;
            border-bottom: 1px solid #222;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            border-bottom: 1px solid #777;
            padding: 1.15mm 1.2mm;
            font-size: 6.8px;
            line-height: 1.1;
        }

        th {
            text-align: left;
            font-weight: 700;
        }

        td.amount,
        th.amount {
            text-align: right;
            width: 24%;
            white-space: nowrap;
        }

        tr:last-child td {
            border-bottom: none;
        }

        .total {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-top: 1px solid #222;
            border-bottom: 1px solid #222;
            padding: 2mm 1.5mm;
            font-size: 8px;
            font-weight: 700;
        }

        .total-amount {
            font-size: 10px;
        }

        .signature {
            margin-top: auto;
            padding: 2.5mm 1.5mm 1.5mm;
            min-height: 16mm;
        }

        .signature-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 4mm;
        }

        .sign-box {
            text-align: center;
            font-size: 6px;
        }

        .sign-line {
            border-top: 1px solid #222;
            margin-bottom: 1mm;
            height: 0;
        }

        .footer-note {
            text-align: center;
            padding-top: 0.8mm;
            font-size: 5.5px;
            color: #555;
        }

        .status-stamp {
            position: absolute;
            right: 2mm;
            top: 21mm;
            font-size: 6px;
            font-weight: 700;
            text-transform: uppercase;
            border: 1px solid #666;
            padding: 1mm;
        }

        @media screen {

            body {
                background: #e5e7eb;
                padding: 10px;
            }

            .page {
                max-width: 1120px;
                margin: auto;
                background: white;
                padding: 5px;
            }

            .print-bar {
                max-width: 1120px;
                margin: 0 auto 10px;
                padding: 8px 10px;
                background: white;
                border: 1px solid #d1d5db;
                display: flex;
                justify-content: space-between;
                align-items: center;
            }

            .print-button {
                border: 0;
                background: #059669;
                color: white;
                border-radius: 6px;
                padding: 8px 14px;
                cursor: pointer;
                font-weight: 700;
            }
        }

        @media print {

            .print-bar {
                display: none;
            }

            body {
                padding: 0;
            }

            .page {
                padding: 0;
            }

        }

    </style>

</head>

<body>


<div class="print-bar">

    <div>

        <strong>
            {{ $voucher->voucher_no }}
        </strong>

        <span style="margin-left:10px;color:#6b7280;">
            {{ $voucher->voucher_type_label }}
        </span>

    </div>

    <button
        type="button"
        class="print-button"
        onclick="window.print()">

        Print Challan

    </button>

</div>


<div class="page">

@foreach($copies as $copy)

    <div class="copy">


        {{-- Header --}}
        <div class="copy-header">

            <div class="copy-title">
                {{ $copy['title'] }}
            </div>

            <div class="brand">

                {{-- Optional logo --}}
                @if(file_exists(public_path('images/gattc-logo.png')))

                    <img
                        src="{{ asset('images/gattc-logo.png') }}"
                        class="brand-logo"
                        alt="GATTC">

                @endif

                <div>

                    <div class="brand-name">
                        GOVERNMENT ADVANCE TECHNICAL
                        TRAINING CENTRE
                    </div>

                    <div class="brand-address">
                        16-A Industrial Estate,
                        Opposite BRT TEVTA Stop,
                        Hayatabad, Peshawar
                        | 091-5881389
                    </div>

                </div>

            </div>

        </div>


        <div class="status-stamp">
            {{ strtoupper($courseType) }}
        </div>


        {{-- Voucher Meta --}}
        <div class="meta">

            <div class="meta-cell">

                <span class="label">
                    Voucher No.
                </span>

                <span class="value">
                    {{ $voucher->voucher_no }}
                </span>

            </div>

            <div class="meta-cell">

                <span class="label">
                    Issue Date
                </span>

                <span class="value">
                    {{ optional($voucher->issue_date)->format('d-m-Y') }}
                </span>

            </div>

            <div class="meta-cell">

                <span class="label">
                    Due Date
                </span>

                <span class="value">
                    {{ optional($voucher->due_date)->format('d-m-Y') }}
                </span>

            </div>

            <div class="meta-cell">

                <span class="label">
                    Session
                </span>

                <span class="value">
                    {{ $session }}
                </span>

            </div>

        </div>


        {{-- Bank --}}
        <div class="bank-box">

            <span class="label">
                Deposit In Bank
            </span>

            <div class="bank-name">
                {{ $voucher->bank_name }}
            </div>

            <div class="bank-account">
                A/C {{ $voucher->account_number }}
            </div>

            <div style="margin-top:1mm;">

                <span class="label"
                      style="display:inline;">
                    Account Title:
                </span>

                <span class="value"
                      style="font-size:6.8px;">
                    {{ $voucher->account_title }}
                </span>

            </div>

            @if($voucher->iban)

                <div style="margin-top:0.8mm;">

                    <span class="label"
                          style="display:inline;">
                        IBAN:
                    </span>

                    <span class="value"
                          style="font-size:6.8px;">
                        {{ $voucher->iban }}
                    </span>

                </div>

            @endif

        </div>


        {{-- Student --}}
        <div class="info-row">

            <div class="cell">

                <span class="label">
                    Student Name
                </span>

                <span class="value">
                    {{ $voucher->applicant_name }}
                </span>

            </div>

            <div class="cell">

                <span class="label">
                    CNIC / Form-B
                </span>

                <span class="value">
                    {{ $voucher->cnic }}
                </span>

            </div>

        </div>


        <div class="info-row">

            <div class="cell">

                <span class="label">
                    Father's Name
                </span>

                <span class="value">
                    {{ $voucher->father_name ?: '—' }}
                </span>

            </div>

            <div class="cell">

                <span class="label">
                    Contact No.
                </span>

                <span class="value">
                    {{ $voucher->phone }}
                </span>

            </div>

        </div>


        {{-- Course --}}
        <div class="info-row">

            <div class="cell">

                <span class="label">
                    Trade / Technology
                </span>

                <span class="value">
                    {{ $courseTitle }}
                </span>

            </div>

            <div class="cell">

                <span class="label">
                    Duration
                </span>

                <span class="value">
                    {{ $duration }}
                </span>

            </div>

        </div>


        <div class="single-row">

            <div class="cell">

                <span class="label">
                    Institute
                </span>

                <span class="value">
                    {{ $institute }}
                </span>

            </div>

        </div>


        @if($voucher->admission)

            <div class="single-row">

                <div class="cell">

                    <span class="label">
                        Admission No.
                    </span>

                    <span class="value">
                        {{ $voucher->admission->admission_no }}
                    </span>

                </div>

            </div>

        @endif


        {{-- Deposit Details --}}
        <div class="section-title">
            DETAILS OF DEPOSIT
        </div>


        <table>

            <thead>

                <tr>

                    <th>
                        Description
                    </th>

                    <th class="amount">
                        Rs.
                    </th>

                </tr>

            </thead>

            <tbody>

                <tr>

                    <td>
                        {{ $feeLabel }}
                    </td>

                    <td class="amount">
                        {{ number_format($voucher->amount, 2) }}
                    </td>

                </tr>

                @if($voucher->voucher_type !== 'hostel')

                    <tr>
                        <td>Tuition / Training Fee</td>
                        <td class="amount">—</td>
                    </tr>

                    <tr>
                        <td>Registration / Other Fee</td>
                        <td class="amount">—</td>
                    </tr>

                @endif

                <tr>
                    <td>Miscellaneous Charges</td>
                    <td class="amount">—</td>
                </tr>

            </tbody>

        </table>


        {{-- Total --}}
        <div class="total">

            <span>
                TOTAL FEE
            </span>

            <span class="total-amount">
                Rs.
                {{ number_format($voucher->amount, 2) }}
            </span>

        </div>


        {{-- Signature --}}
        <div class="signature">

            <div class="signature-grid">

                <div class="sign-box">

                    <div class="sign-line"></div>

                    Depositor Signature

                </div>

                <div class="sign-box">

                    <div class="sign-line"></div>

                    Bank Officer
                    Stamp & Sign

                </div>

            </div>


            <div class="footer-note">

                {{ $copy['sub'] }}

            </div>

        </div>


    </div>

@endforeach

</div>


<script>

window.addEventListener('load', function () {

    // Automatically open print dialog when directly
    // opening the printable voucher URL.
    // Remove this line if you prefer preview-first.

    // window.print();

});

</script>

</body>

</html>