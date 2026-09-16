@php
    $admission = $studentCard->admission;
    $course = $admission?->course;
@endphp

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <title>
        Student Card {{ $studentCard->card_no }}
    </title>

    <style>

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            padding: 30px;
            background: #eef2f7;
            font-family: Arial, Helvetica, sans-serif;
        }

        .actions {
            max-width: 380px;
            margin: 0 auto 20px;
            display: flex;
            gap: 10px;
        }

        .actions a,
        .actions button {
            flex: 1;
            border: 0;
            border-radius: 7px;
            padding: 10px;
            font-size: 13px;
            font-weight: 700;
            cursor: pointer;
            text-align: center;
            text-decoration: none;
        }

        .back {
            background: #e5e7eb;
            color: #374151;
        }

        .print {
            background: #059669;
            color: #fff;
        }

        .card {
            width: 85.6mm;
            height: 54mm;
            margin: auto;
            border: 1px solid #111827;
            border-radius: 4mm;
            overflow: hidden;
            background: #fff;
            position: relative;
        }

        .card-header {
            height: 13mm;
            border-bottom: 1px solid #d1d5db;
            display: flex;
            align-items: center;
            gap: 2mm;
            padding: 2mm 3mm;
        }

        .logo {
            width: 9mm;
            height: 9mm;
            object-fit: contain;
        }

        .institution {
            font-size: 8px;
            font-weight: 800;
            line-height: 1.2;
        }

        .address {
            font-size: 5.5px;
            color: #6b7280;
            margin-top: 1mm;
        }

        .card-title {
            position: absolute;
            right: 3mm;
            top: 3mm;
            font-size: 6px;
            font-weight: 700;
        }

        .body {
            display: grid;
            grid-template-columns: 19mm 1fr;
            gap: 3mm;
            padding: 3mm;
        }

        .photo {
            width: 18mm;
            height: 23mm;
            border: 1px solid #9ca3af;
            object-fit: cover;
        }

        .field {
            margin-bottom: 1.5mm;
        }

        .label {
            font-size: 5px;
            text-transform: uppercase;
            color: #6b7280;
            font-weight: 700;
        }

        .value {
            font-size: 7px;
            color: #111827;
            font-weight: 700;
        }

        .footer {
            position: absolute;
            bottom: 2mm;
            left: 3mm;
            right: 3mm;
            border-top: 1px solid #d1d5db;
            padding-top: 1.3mm;
            display: flex;
            justify-content: space-between;
            font-size: 5px;
        }

        @media print {

            body {
                background: white;
                padding: 0;
            }

            .actions {
                display: none;
            }

            .card {
                margin: 0;
            }

        }

    </style>

</head>

<body>

<div class="actions">

    <a
        href="{{ route(
            'admin.fee-payments.index'
        ) }}"
        class="back">
        Back
    </a>

    <button
        onclick="window.print()"
        class="print">
        Print Student Card
    </button>

</div>


<div class="card">

    <div class="card-title">
        STUDENT ID CARD
    </div>


    <div class="card-header">

        @if(
            file_exists(
                public_path(
                    'images/gattc-logo.png'
                )
            )
        )

            <img
                src="{{ asset(
                    'images/gattc-logo.png'
                ) }}"
                class="logo"
                alt="GATTC">

        @endif


        <div>

            <div class="institution">
                GOVERNMENT ADVANCE
                TECHNICAL TRAINING CENTRE
            </div>

            <div class="address">
                16-A Industrial Estate,
                Hayatabad, Peshawar
                | 091-5881389
            </div>

        </div>

    </div>


    <div class="body">

        {{-- Photo --}}
        @if($studentCard->photo)

            <img
                src="{{ asset(
                    'storage/' .
                    $studentCard->photo
                ) }}"
                class="photo"
                alt="Student Photo">

        @else

            <div class="photo"></div>

        @endif


        <div>

            <div class="field">

                <div class="label">
                    Student Name
                </div>

                <div class="value">
                    {{ $admission->student_name }}
                </div>

            </div>


            <div class="field">

                <div class="label">
                    Father Name
                </div>

                <div class="value">
                    {{ $admission->father_name }}
                </div>

            </div>


            <div class="field">

                <div class="label">
                    Admission No.
                </div>

                <div class="value">
                    {{ $admission->admission_no }}
                </div>

            </div>


            <div class="field">

                <div class="label">
                    CNIC
                </div>

                <div class="value">
                    {{ $admission->cnic }}
                </div>

            </div>


            <div class="field">

                <div class="label">
                    Course
                </div>

                <div class="value">
                    {{ $course?->title }}
                </div>

            </div>

        </div>

    </div>


    <div class="footer">

        <span>
            Card No:
            <strong>
                {{ $studentCard->card_no }}
            </strong>
        </span>

        <span>
            Issued:
            {{ optional(
                $studentCard->issued_at
            )->format('d-m-Y') }}
        </span>

    </div>

</div>


<script>

window.addEventListener(
    'load',
    function () {
        // Preview first.
        // window.print();
    }
);

</script>

</body>

</html>