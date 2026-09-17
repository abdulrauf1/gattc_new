<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <title>
        GATTC Bank Account
    </title>

    <style>

        @page {
            size: A4 portrait;
            margin: 15mm;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: Arial, Helvetica, sans-serif;
            color: #111827;
        }

        .toolbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 20px;
        }

        .toolbar button {
            border: 0;
            border-radius: 6px;
            background: #111827;
            color: white;
            padding: 9px 14px;
            cursor: pointer;
            font-weight: 700;
        }

        .document {
            border: 1px solid #1f2937;
        }

        .header {
            text-align: center;
            padding: 20px;
            border-bottom: 2px solid #111827;
        }

        .header h1 {
            margin: 0;
            font-size: 20px;
        }

        .header p {
            margin: 7px 0 0;
            font-size: 11px;
            color: #4b5563;
        }

        .purpose {
            padding: 15px;
            text-align: center;
            font-size: 12px;
            font-weight: 700;
            text-transform: uppercase;
            background: #f3f4f6;
            border-bottom: 1px solid #d1d5db;
        }

        .row {
            display: grid;
            grid-template-columns: 180px 1fr;
            border-bottom: 1px solid #d1d5db;
        }

        .label,
        .value {
            padding: 13px 15px;
        }

        .label {
            font-weight: 700;
            color: #4b5563;
            background: #f9fafb;
            border-right: 1px solid #d1d5db;
        }

        .value {
            font-weight: 600;
        }

        .footer {
            text-align: center;
            font-size: 9px;
            color: #6b7280;
            padding: 25px 15px;
        }

        @media print {

            .toolbar {
                display: none;
            }

        }

    </style>

</head>

<body>

<div class="toolbar">

    <strong>
        GATTC Bank Account Information
    </strong>

    <button onclick="window.print()">
        Print
    </button>

</div>


<div class="document">

    <div class="header">

        <h1>
            GOVERNMENT ADVANCE TECHNICAL
            TRAINING CENTRE
        </h1>

        <p>
            16-A Industrial Estate,
            Opposite BRT TEVTA Stop,
            Hayatabad, Peshawar
            | 091-5881389
        </p>

    </div>


    <div class="purpose">
        {{ $bankAccount->purpose }}
    </div>


    <div class="row">

        <div class="label">
            Bank
        </div>

        <div class="value">
            {{ $bankAccount->bank_name }}
        </div>

    </div>


    <div class="row">

        <div class="label">
            Account Title
        </div>

        <div class="value">
            {{ $bankAccount->account_title }}
        </div>

    </div>


    <div class="row">

        <div class="label">
            Account Number
        </div>

        <div class="value">
            {{ $bankAccount->account_number }}
        </div>

    </div>


    <div class="row">

        <div class="label">
            IBAN
        </div>

        <div class="value">
            {{ $bankAccount->iban ?: '—' }}
        </div>

    </div>


    <div class="row">

        <div class="label">
            Branch
        </div>

        <div class="value">
            {{ $bankAccount->branch_name ?: '—' }}
        </div>

    </div>


    <div class="row">

        <div class="label">
            Branch Code
        </div>

        <div class="value">
            {{ $bankAccount->branch_code ?: '—' }}
        </div>

    </div>


    <div class="row">

        <div class="label">
            Status
        </div>

        <div class="value">
            {{ $bankAccount->status
                ? 'Active'
                : 'Inactive'
            }}
        </div>

    </div>


    <div class="footer">

        Official GATTC bank-account information.

    </div>

</div>

</body>

</html>