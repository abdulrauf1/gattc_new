<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>GATTC Fee Challan - {{ $voucher->voucher_no }}</title>

    <style>
        @page {
            size: A4 landscape;
            margin: 6mm;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            padding: 0;
            font-family: Arial, Helvetica, sans-serif;
            color: #111;
            background: #fff;
            font-size: 9px;
        }

        .voucher-page {
            width: 100%;
            display: grid;
            grid-template-columns: repeat(3, 1fr);
        }

        .copy {
            border: 1.5px solid #111;
            min-height: 190mm;
            padding: 3mm 4mm;
            position: relative;
        }

        .copy:not(:last-child) {
            border-right: 1px dashed #555;
        }

        .copy-title {
            border-bottom: 1.5px solid #111;
            padding-bottom: 3px;
            margin-bottom: 4px;
            font-size: 9.5px;
            font-weight: bold;
        }

        .header {
            display: flex;
            align-items: center;
            gap: 6px;
            border-bottom: 1.5px solid #111;
            padding-bottom: 5px;
            margin-bottom: 5px;
        }

        .header img {
            width: 40px;
            height: 40px;
            flex-shrink: 0;
        }

        .header-text {
            text-align: center;
            flex: 1;
        }

        .header-text .province {
            font-size: 10px;
            font-weight: bold;
            line-height: 1.25;
        }

        .header-text .authority {
            font-size: 8.5px;
            font-weight: bold;
            line-height: 1.25;
        }

        .field-row {
            display: flex;
            justify-content: space-between;
            border-bottom: 1px dotted #999;
            padding: 2.5px 0;
        }

        .field-row .f-label {
            font-weight: bold;
            white-space: nowrap;
        }

        .field-row .f-value {
            flex: 1;
            border-bottom: 1px solid #111;
            margin-left: 4px;
            min-height: 10px;
        }

        .field-row.tight .f-value {
            border-bottom: none;
        }

        .inline-fields {
            display: flex;
            justify-content: space-between;
            border-bottom: 1px dotted #999;
            padding: 2.5px 0;
        }

        .checkbox {
            display: inline-block;
            width: 8px;
            height: 8px;
            border: 1px solid #111;
            margin: 0 2px 0 4px;
            vertical-align: middle;
        }

        .checkbox.checked {
            background: #111;
        }

        .deposit-title {
            text-align: center;
            font-weight: bold;
            font-size: 9.5px;
            margin-top: 7px;
            margin-bottom: 3px;
        }

        .deposit-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 8.5px;
        }

        .deposit-table td {
            padding: 2px 0;
        }

        .deposit-table td.item {
            width: 65%;
        }

        .deposit-table td.amt {
            width: 35%;
            border-bottom: 1px solid #111;
            text-align: right;
            padding-right: 2px;
        }

        .total-row td {
            font-weight: bold;
            padding-top: 6px;
        }

        .total-row td.amt {
            border-bottom: 2px solid #111;
            padding-bottom: 2px;
        }

        .signature {
            display: flex;
            justify-content: space-between;
            margin-top: 14px;
        }

        .signature div {
            width: 45%;
            text-align: center;
            border-top: 1px solid #111;
            padding-top: 3px;
            font-size: 8px;
        }

        @media print {
            .no-print {
                display: none !important;
            }

            .voucher-page {
                page-break-inside: avoid;
            }
        }

        .print-button {
            margin: 10px auto;
            display: block;
            padding: 10px 20px;
            font-size: 15px;
            cursor: pointer;
        }
    </style>
</head>

<body>

<button class="print-button no-print" onclick="window.print()">
    Print Challan
</button>

<div class="voucher-page">

    @foreach([
        "Student's Copy",
        'Institute/Centre Copy',
        "Bank's Copy",
    ] as $copy)

        <div class="copy">

            <div class="copy-title">
                Admission Fee Challan Receipt ({{ $copy }})
            </div>

            <div class="header">
                <img src="{{ asset('images/kp-tevta-logo.png') }}" alt="KP-TEVTA Logo">
                <div class="header-text">
                    <div class="province">KHYBER PAKHTUNKHWA</div>
                    <div class="authority">
                        TECHNICAL EDUCATION &amp; VOCATIONAL<br>
                        TRAINING AUTHORITY
                    </div>
                </div>
                <img src="{{ asset('images/gattc-logo.png') }}" alt="GATTC Logo">
            </div>

            <div class="inline-fields">
                <span><span class="f-label">Receipt No:</span> {{ $voucher->voucher_no }}</span>
                <span><span class="f-label">Dated:</span> {{ $voucher->issue_date?->format('d-m-Y') }}</span>
            </div>

            <div class="field-row">
                <span class="f-label">Admission No:</span>
                <span class="f-value">{{ $voucher->admission_no ?? '' }}</span>
            </div>

            <div class="field-row">
                <span class="f-label">Account Title:</span>
                <span class="f-value">{{ $voucher->account_title ?? 'PRINCIPAL GOVT ADV TECH TRG CENT' }}</span>
            </div>

            <div class="field-row">
                <span class="f-label">IBAN:</span>
                <span class="f-value">{{ $voucher->iban ?? 'PK35 KHYB 0101 0030 0092 4945' }}</span>
            </div>

            <div class="inline-fields">
                <span>
                    <span class="f-label">Shift:</span>
                    Morning <span class="checkbox {{ ($voucher->shift ?? '') === 'Morning' ? 'checked' : '' }}"></span>
                    Evening <span class="checkbox {{ ($voucher->shift ?? '') === 'Evening' ? 'checked' : '' }}"></span>
                </span>
                <span><span class="f-label">Session:</span> {{ $voucher->session ?? '' }}</span>
            </div>

            <div class="field-row">
                <span class="f-label">Name of Institute:</span>
                <span class="f-value">GATTC Hayatabad Peshawar</span>
            </div>

            <div class="field-row">
                <span class="f-label">Name of Student:</span>
                <span class="f-value">{{ $voucher->applicant_name }}</span>
            </div>

            <div class="field-row">
                <span class="f-label">CNIC/FORM-B:</span>
                <span class="f-value">{{ $voucher->cnic }}</span>
            </div>

            <div class="field-row">
                <span class="f-label">Contact No:</span>
                <span class="f-value">{{ $voucher->phone }}</span>
            </div>

            <div class="field-row">
                <span class="f-label">Father's Name:</span>
                <span class="f-value">{{ $voucher->father_name }}</span>
            </div>

            <div class="inline-fields">
                <span><span class="f-label">Trade/Technology:</span> {{ $voucher->course?->title ?? '' }}</span>
                <span><span class="f-label">Class No:</span> {{ $voucher->class_no ?? '' }}</span>
            </div>

            <div class="field-row">
                <span class="f-label">Institute Type:</span>
                <span class="f-value">{{ $voucher->institute_type ?? 'Vocational' }}</span>
            </div>

            <div class="inline-fields">
                <span class="f-label">Qualification Type:</span>
                <span>
                    CBT <span class="checkbox {{ ($voucher->qualification_type ?? '') === 'CBT' ? 'checked' : '' }}"></span>
                    Traditional <span class="checkbox {{ ($voucher->qualification_type ?? '') === 'Traditional' ? 'checked' : '' }}"></span>
                </span>
            </div>

            <div class="field-row">
                <span class="f-label">Duration of Course<br>(Certificate/Short Course):</span>
                <span class="f-value">{{ $voucher->course_duration ?? '' }}</span>
            </div>

            <div class="deposit-title">Details of Deposit</div>

            <table class="deposit-table">
                <tr>
                    <td class="item">1&nbsp;&nbsp;Admission Fee</td>
                    <td class="amt">{{ $voucher->fee_items['admission'] ?? '' }}</td>
                </tr>
                <tr>
                    <td class="item">2&nbsp;&nbsp;Tuition Fee</td>
                    <td class="amt">{{ $voucher->fee_items['tuition'] ?? '' }}</td>
                </tr>
                <tr>
                    <td class="item">3&nbsp;&nbsp;Board Registration Fee</td>
                    <td class="amt">{{ $voucher->fee_items['board_registration'] ?? '' }}</td>
                </tr>
                <tr>
                    <td class="item">4&nbsp;&nbsp;Certificate Fee</td>
                    <td class="amt">{{ $voucher->fee_items['certificate'] ?? '' }}</td>
                </tr>
                <tr>
                    <td class="item">5&nbsp;&nbsp;Examination Fee</td>
                    <td class="amt">{{ $voucher->fee_items['examination'] ?? '' }}</td>
                </tr>
                <tr>
                    <td class="item">6&nbsp;&nbsp;Fine/Struck Off, If any</td>
                    <td class="amt">{{ $voucher->fee_items['fine'] ?? '' }}</td>
                </tr>
                <tr>
                    <td class="item">7&nbsp;&nbsp;Identity Card Fee</td>
                    <td class="amt">{{ $voucher->fee_items['identity_card'] ?? '' }}</td>
                </tr>
                <tr>
                    <td class="item">8&nbsp;&nbsp;Miscellaneous Charges</td>
                    <td class="amt">{{ $voucher->fee_items['misc'] ?? '' }}</td>
                </tr>
                <tr>
                    <td class="item">9&nbsp;&nbsp;Re-Admission Fee</td>
                    <td class="amt">{{ $voucher->fee_items['re_admission'] ?? '' }}</td>
                </tr>
                <tr class="total-row">
                    <td class="item">TOTAL FEE:</td>
                    <td class="amt">Rs. {{ number_format($voucher->amount, 2) }}</td>
                </tr>
            </table>

            <div class="signature">
                <div>Depositor Sign</div>
                <div>Bank Officer<br>Stamp &amp; Sign</div>
            </div>

        </div>

    @endforeach

</div>

</body>
</html>