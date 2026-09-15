<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">

    <title>GATTC Fee Challan - {{ $voucher->voucher_no }}</title>

    <style>
        @page {
            size: A4 landscape;
            margin: 8mm;
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
        }

        .voucher-page {
            width: 100%;
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 4mm;
        }

        .copy {
            border: 1.5px solid #111;
            min-height: 190mm;
            padding: 4mm;
            position: relative;
        }

        .copy:not(:last-child) {
            border-right: 1px dashed #555;
        }

        .copy-title {
            text-align: center;
            font-size: 14px;
            font-weight: bold;
            border-bottom: 1px solid #111;
            padding-bottom: 3px;
            margin-bottom: 5px;
        }

        .logo {
            text-align: center;
            margin-bottom: 3px;
        }

        .logo img {
            width: 70px;
            height: auto;
        }

        .college-name {
            text-align: center;
            font-size: 11px;
            font-weight: bold;
            line-height: 1.2;
        }

        .challan-title {
            text-align: center;
            font-size: 13px;
            font-weight: bold;
            margin-top: 4px;
            margin-bottom: 7px;
            text-transform: uppercase;
        }

        .voucher-no {
            border: 1px solid #111;
            padding: 4px;
            text-align: center;
            font-size: 10px;
            margin-bottom: 6px;
        }

        .section-title {
            font-size: 9px;
            font-weight: bold;
            background: #eee;
            border: 1px solid #111;
            padding: 3px;
            margin-top: 5px;
        }

        .detail-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 8.5px;
        }

        .detail-table td {
            border: 1px solid #111;
            padding: 3px;
            vertical-align: top;
        }

        .label {
            width: 40%;
            font-weight: bold;
        }

        .amount-box {
            border: 2px solid #111;
            margin-top: 8px;
            padding: 6px;
            text-align: center;
        }

        .amount-label {
            font-size: 9px;
            font-weight: bold;
        }

        .amount {
            font-size: 18px;
            font-weight: bold;
            margin-top: 3px;
        }

        .instructions {
            font-size: 7.5px;
            line-height: 1.35;
            margin-top: 8px;
        }

        .signature {
            position: absolute;
            bottom: 9mm;
            left: 4mm;
            right: 4mm;
            display: flex;
            justify-content: space-between;
            font-size: 8px;
        }

        .signature div {
            width: 45%;
            text-align: center;
            border-top: 1px solid #111;
            padding-top: 3px;
        }

        .footer {
            position: absolute;
            bottom: 3mm;
            left: 4mm;
            right: 4mm;
            text-align: center;
            font-size: 7px;
        }

        @media print {
            body {
                background: white;
            }

            .voucher-page {
                page-break-inside: avoid;
            }

            .no-print {
                display: none !important;
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
        'BANK COPY',
        'GATTC OFFICE COPY',
        'APPLICANT COPY'
    ] as $copy)

        <div class="copy">

            <div class="copy-title">
                {{ $copy }}
            </div>

            <div class="logo">
                <img
                    src="{{ asset('images/gattc-logo.png') }}"
                    alt="GATTC Logo"
                >
            </div>

            <div class="college-name">
                Government Advance Technical Training Centre<br>
                Hayatabad, Peshawar
            </div>

            <div class="challan-title">
                Fee Challan
            </div>

            <div class="voucher-no">
                <strong>Voucher No:</strong>
                {{ $voucher->voucher_no }}
            </div>

            <div class="section-title">
                APPLICANT INFORMATION
            </div>

            <table class="detail-table">
                <tr>
                    <td class="label">Applicant</td>
                    <td>{{ $voucher->applicant_name }}</td>
                </tr>

                <tr>
                    <td class="label">Father Name</td>
                    <td>{{ $voucher->father_name }}</td>
                </tr>

                <tr>
                    <td class="label">CNIC</td>
                    <td>{{ $voucher->cnic }}</td>
                </tr>

                <tr>
                    <td class="label">Phone</td>
                    <td>{{ $voucher->phone }}</td>
                </tr>
            </table>

            <div class="section-title">
                FEE INFORMATION
            </div>

            <table class="detail-table">

                <tr>
                    <td class="label">Voucher Type</td>
                    <td>
                        {{ ucfirst($voucher->voucher_type) }}
                    </td>
                </tr>

                <tr>
                    <td class="label">Course</td>
                    <td>
                        {{ $voucher->course?->title ?? 'Hostel Fee' }}
                    </td>
                </tr>

                <tr>
                    <td class="label">Issue Date</td>
                    <td>
                        {{ $voucher->issue_date?->format('d-m-Y') }}
                    </td>
                </tr>

                <tr>
                    <td class="label">Due Date</td>
                    <td>
                        {{ $voucher->due_date?->format('d-m-Y') }}
                    </td>
                </tr>

            </table>

            <div class="section-title">
                BANK INFORMATION
            </div>

            <table class="detail-table">

                <tr>
                    <td class="label">Bank</td>
                    <td>
                        {{ $voucher->bank_name }}
                    </td>
                </tr>

                <tr>
                    <td class="label">Account Title</td>
                    <td>
                        {{ $voucher->account_title }}
                    </td>
                </tr>

                <tr>
                    <td class="label">Account No.</td>
                    <td>
                        {{ $voucher->account_number }}
                    </td>
                </tr>

                <tr>
                    <td class="label">IBAN</td>
                    <td>
                        {{ $voucher->iban }}
                    </td>
                </tr>

                <tr>
                    <td class="label">Branch</td>
                    <td>
                        {{ $voucher->branch_name }}
                    </td>
                </tr>

            </table>

            <div class="amount-box">
                <div class="amount-label">
                    TOTAL AMOUNT
                </div>

                <div class="amount">
                    Rs. {{ number_format($voucher->amount, 2) }}
                </div>
            </div>

            <div class="instructions">
                <strong>Instructions:</strong><br>
                1. Deposit the fee in the designated Bank of Khyber account.<br>
                2. Keep the applicant copy safely.<br>
                3. Submit/upload the paid bank copy for verification.<br>
                4. Admission will be processed after payment verification.
            </div>

            <div class="signature">
                <div>
                    Applicant Signature
                </div>

                <div>
                    Bank / Office Stamp
                </div>
            </div>

            <div class="footer">
                GATTC Hayatabad Peshawar
            </div>

        </div>

    @endforeach

</div>

</body>
</html>