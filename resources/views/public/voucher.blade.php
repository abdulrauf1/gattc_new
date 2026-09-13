<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">

    <title>Admission Fee Voucher - {{ $voucher->voucher_no }}</title>

    <style>
        @page {
            size: A4 portrait;
            margin: 8mm;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            background: #eeeeee;
            color: #111111;
            font-family: Arial, Helvetica, sans-serif;
            font-size: 10px;
        }

        .page {
            width: 194mm;
            margin: 0 auto;
            background: #ffffff;
        }

        .copy {
            min-height: 88mm;
            border: 1px solid #222222;
            margin-bottom: 4mm;
            padding: 5mm;
            page-break-inside: avoid;
        }

        .copy:last-child {
            margin-bottom: 0;
        }

        .copy-title {
            text-align: center;
            font-size: 12px;
            font-weight: bold;
            margin-bottom: 4px;
        }

        .authority {
            text-align: center;
            font-size: 11px;
            font-weight: bold;
            line-height: 1.35;
        }

        .voucher-heading {
            text-align: center;
            font-size: 13px;
            font-weight: bold;
            margin: 4px 0 7px;
            text-decoration: underline;
        }

        .top-details {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 3px 12px;
            margin-bottom: 5px;
        }

        .field {
            border-bottom: 1px dotted #555555;
            min-height: 17px;
        }

        .field strong {
            font-weight: bold;
        }

        .bank-box {
            border: 1px solid #222222;
            padding: 4px;
            margin-bottom: 5px;
        }

        .bank-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 3px 12px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 4px;
        }

        th,
        td {
            border: 1px solid #222222;
            padding: 3px 4px;
            height: 17px;
        }

        th {
            text-align: left;
            background: #f1f1f1;
        }

        .amount {
            width: 28%;
            text-align: right;
        }

        .total-row td {
            font-weight: bold;
            font-size: 11px;
        }

        .signatures {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            gap: 12px;
            margin-top: 12px;
            text-align: center;
        }

        .signature-line {
            border-top: 1px solid #222222;
            padding-top: 4px;
        }

        .footer-note {
            margin-top: 5px;
            font-size: 9px;
            text-align: center;
        }

        .checkbox {
            display: inline-block;
            width: 10px;
            height: 10px;
            border: 1px solid #222222;
            margin: 0 3px;
            vertical-align: middle;
        }

        .print-button {
            display: block;
            margin: 15px auto;
            padding: 10px 20px;
            background: #123b70;
            color: white;
            border: 0;
            cursor: pointer;
        }

        @media print {
            body {
                background: white;
            }

            .page {
                width: 100%;
            }

            .print-button {
                display: none;
            }
        }
    </style>
</head>

<body>
    <button class="print-button" onclick="window.print()">
        Print Voucher
    </button>

    <main class="page">
        @foreach ([
            "Student's Copy",
            'Institute/Centre Copy',
            "Bank's Copy"
        ] as $copyTitle)
            <section class="copy">
                <div class="copy-title">
                    Admission Fee Challan Receipt
                    ({{ $copyTitle }})
                </div>

                <div class="authority">
                    KHYBER PAKHTUNKHWA<br>
                    TECHNICAL EDUCATION & VOCATIONAL<br>
                    TRAINING AUTHORITY
                </div>

                <div class="voucher-heading">
                    GATTC HAYATABAD PESHAWAR
                </div>

                <div class="top-details">
                    <div class="field">
                        <strong>Receipt No:</strong>
                        {{ $voucher->voucher_no }}
                    </div>

                    <div class="field">
                        <strong>Dated:</strong>
                        {{ $voucher->issue_date?->format('d-m-Y') }}
                    </div>

                    <div class="field">
                        <strong>Admission No:</strong>
                        {{ $voucher->admission?->admission_no ?? '____________' }}
                    </div>

                    <div class="field">
                        <strong>Session:</strong>
                        {{ $voucher->admissionSession?->name ?? '____________' }}
                    </div>

                    <div class="field">
                        <strong>Shift:</strong>
                        <span class="checkbox"></span> Morning
                        <span class="checkbox"></span> Evening
                    </div>

                    <div class="field">
                        <strong>Category:</strong>
                        {{ strtoupper(str_replace('_', ' ', $voucher->voucher_category)) }}
                    </div>
                </div>

                <div class="bank-box">
                    <div class="bank-grid">
                        <div>
                            <strong>Bank:</strong>
                            {{ $voucher->bank_name }}
                        </div>

                        <div>
                            <strong>Branch:</strong>
                            {{ $voucher->branch_name }}
                        </div>

                        <div>
                            <strong>Account Title:</strong>
                            {{ $voucher->account_title }}
                        </div>

                        <div>
                            <strong>Account No:</strong>
                            {{ $voucher->account_number ?: '____________' }}
                        </div>

                        <div style="grid-column: 1 / -1;">
                            <strong>IBAN:</strong>
                            {{ $voucher->iban ?: '____________' }}
                        </div>
                    </div>
                </div>

                <div class="top-details">
                    <div class="field">
                        <strong>Name of Institute:</strong>
                        GATTC Hayatabad Peshawar
                    </div>

                    <div class="field">
                        <strong>Institute Type:</strong>
                        Vocational
                    </div>

                    <div class="field">
                        <strong>Name of Student:</strong>
                        {{ $voucher->applicant_name }}
                    </div>

                    <div class="field">
                        <strong>CNIC/FORM-B:</strong>
                        {{ $voucher->cnic }}
                    </div>

                    <div class="field">
                        <strong>Contact No:</strong>
                        {{ $voucher->phone }}
                    </div>

                    <div class="field">
                        <strong>Father's Name:</strong>
                        {{ $voucher->father_name }}
                    </div>

                    <div class="field">
                        <strong>Trade/Technology:</strong>
                        {{ $voucher->course?->name ?? '____________' }}
                    </div>

                    <div class="field">
                        <strong>Class No:</strong>
                        {{ $voucher->courseBatch?->name ?? '____________' }}
                    </div>

                    <div class="field">
                        <strong>Qualification Type:</strong>
                        <span class="checkbox"></span> CBT
                        <span class="checkbox"></span> Traditional
                    </div>

                    <div class="field">
                        <strong>Duration:</strong>
                        {{ $voucher->course?->duration ?? '____________' }}
                    </div>
                </div>

                <table>
                    <thead>
                        <tr>
                            <th style="width: 8%;">S.No.</th>
                            <th>Details of Deposit</th>
                            <th class="amount">Amount Rs.</th>
                        </tr>
                    </thead>

                    <tbody>
                        <tr>
                            <td>1</td>
                            <td>Admission Fee</td>
                            <td class="amount">
                                {{ number_format($voucher->admission_fee, 2) }}
                            </td>
                        </tr>

                        <tr>
                            <td>2</td>
                            <td>Tuition Fee</td>
                            <td class="amount">
                                {{ number_format($voucher->tuition_fee, 2) }}
                            </td>
                        </tr>

                        <tr>
                            <td>3</td>
                            <td>Board Registration Fee</td>
                            <td class="amount">
                                {{ number_format($voucher->board_registration_fee, 2) }}
                            </td>
                        </tr>

                        <tr>
                            <td>4</td>
                            <td>Certificate Fee</td>
                            <td class="amount">
                                {{ number_format($voucher->certificate_fee, 2) }}
                            </td>
                        </tr>

                        <tr>
                            <td>5</td>
                            <td>Examination Fee</td>
                            <td class="amount">
                                {{ number_format($voucher->examination_fee, 2) }}
                            </td>
                        </tr>

                        <tr>
                            <td>6</td>
                            <td>Fine / Struck Off, If Any</td>
                            <td class="amount">
                                {{ number_format($voucher->fine_fee, 2) }}
                            </td>
                        </tr>

                        <tr>
                            <td>7</td>
                            <td>Identity Card Fee</td>
                            <td class="amount">
                                {{ number_format($voucher->identity_card_fee, 2) }}
                            </td>
                        </tr>

                        <tr>
                            <td>8</td>
                            <td>Miscellaneous Charges</td>
                            <td class="amount">
                                {{ number_format($voucher->miscellaneous_fee, 2) }}
                            </td>
                        </tr>

                        <tr>
                            <td>9</td>
                            <td>Re-Admission Fee</td>
                            <td class="amount">
                                {{ number_format($voucher->readmission_fee, 2) }}
                            </td>
                        </tr>

                        <tr class="total-row">
                            <td colspan="2">TOTAL FEE</td>
                            <td class="amount">
                                Rs. {{ number_format($voucher->amount, 2) }}
                            </td>
                        </tr>
                    </tbody>
                </table>

                <div class="signatures">
                    <div class="signature-line">
                        Depositor Sign
                    </div>

                    <div class="signature-line">
                        Bank Officer
                    </div>

                    <div class="signature-line">
                        Stamp & Sign
                    </div>
                </div>

                <div class="footer-note">
                    Due Date:
                    {{ $voucher->due_date?->format('d-m-Y') }}
                    |
                    Voucher Category:
                    {{ strtoupper(str_replace('_', ' ', $voucher->voucher_category)) }}
                </div>
            </section>
        @endforeach
    </main>
</body>
</html>