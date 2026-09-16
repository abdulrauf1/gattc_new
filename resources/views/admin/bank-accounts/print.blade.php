<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <title>
        {{ $bankAccount->account_title }}
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
            font-family: Arial, Helvetica, sans-serif;
            margin: 0;
            color: #111827;
        }

        .toolbar {
            padding: 10px;
            display: flex;
            justify-content: space-between;
            border-bottom: 1px solid #ddd;
            margin-bottom: 25px;
        }

        .toolbar button {
            background: #111827;
            color: white;
            border: 0;
            padding: 8px 15px;
            border-radius: 6px;
            cursor: pointer;
        }

        .document {
            border: 1px solid #222;
            padding: 25px;
        }

        .header {
            text-align: center;
            border-bottom: 2px solid #111827;
            padding-bottom: 15px;
            margin-bottom: 25px;
        }

        .header h1 {
            margin: 0;
            font-size: 20px;
        }

        .header p {
            margin: 6px 0 0;
            font-size: 12px;
            color: #555;
        }

        .purpose {
            text-align: center;
            font-weight: bold;
            font-size: 14px;
            text-transform: uppercase;
            margin-bottom: 25px;
        }

        .row {
            display: grid;
            grid-template-columns: 180px 1fr;
            border-bottom: 1px solid #ddd;
            padding: 12px 0;
        }

        .label {
            font-weight: bold;
            color: #555;
        }

        .value {
            font-weight: 600;
        }

        .footer {
            margin-top: 45px;
            font-size: 10px;
            color: #666;
            text-align: center;
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
        GATTC Bank Account
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
        <div class="label">Bank</div>
        <div class="value">
            {{ $bankAccount->bank_name }}
        </div>
    </div>


    <div class="row">
        <div class="label">Account Title</div>
        <div class="value">
            {{ $bankAccount->account_title }}
        </div>
    </div>


    <div class="row">
        <div class="label">Account Number</div>
        <div class="value">
            {{ $bankAccount->account_number }}
        </div>
    </div>


    <div class="row">
        <div class="label">IBAN</div>
        <div class="value">
            {{ $bankAccount->iban ?: '—' }}
        </div>
    </div>


    <div class="row">
        <div class="label">Branch</div>
        <div class="value">
            {{ $bankAccount->branch_name ?: '—' }}
        </div>
    </div>


    <div class="row">
        <div class="label">Branch Code</div>
        <div class="value">
            {{ $bankAccount->branch_code ?: '—' }}
        </div>
    </div>


    <div class="row">
        <div class="label">Status</div>
        <div class="value">
            {{ $bankAccount->status
                ? 'Active'
                : 'Inactive'
            }}
        </div>
    </div>


    <div class="footer">

        Official GATTC bank account information

    </div>

</div>

</body>

</html>