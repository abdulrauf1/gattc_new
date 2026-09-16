@php
    use BaconQrCode\Renderer\ImageRenderer;
    use BaconQrCode\Renderer\Image\SvgImageBackEnd;
    use BaconQrCode\Renderer\RendererStyle\RendererStyle;
    use BaconQrCode\Writer;

    $admission = $studentCard->admission;
    $course = $admission?->course;

    // Data encoded into the QR code — student identity + card expiry only.
    // No external route/lookup needed: everything is embedded in the code itself.
    $qrData = implode("\n", [
        'Name: ' . $admission->student_name,
        'F/Name: ' . $admission->father_name,
        'CNIC: ' . $admission->cnic,
        'Card No: ' . $studentCard->card_no,
        'Expiry: ' . (optional($studentCard->expiry_date)->format('d-m-Y') ?: 'N/A'),
    ]);

    // Renders as SVG — no ext-gd required at all.
    $qrRenderer = new ImageRenderer(
        new RendererStyle(240, 0),
        new SvgImageBackEnd()
    );
    $qrSvg = (new Writer($qrRenderer))->writeString($qrData);

    // Strip the XML prolog so the SVG can be embedded inline in the HTML.
    $qrSvg = preg_replace('/^<\?xml.*?\?>\s*/', '', $qrSvg);
@endphp

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <title>
        Student Card - {{ $studentCard->card_no }}
    </title>

    <style>

        @page {
            size: A4 landscape;
            margin: 10mm;
        }

        * {
            box-sizing: border-box;
        }

        html,
        body {
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', Arial, Helvetica, sans-serif;
            color: #0f172a;
            background: white;
            /* Baseline hint so colors survive print even outside @media print */
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
            color-adjust: exact;
        }

        /* ============================================================
           TOOLBAR (screen only)
           ============================================================ */

        .toolbar {
            width: 180mm;
            margin: 0 auto 10mm;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .toolbar-title {
            font-size: 14px;
            font-weight: 700;
        }

        .toolbar-subtitle {
            font-size: 11px;
            color: #6b7280;
            margin-top: 2px;
        }

        .toolbar button,
        .toolbar a {
            border: 0;
            border-radius: 6px;
            padding: 9px 14px;
            text-decoration: none;
            cursor: pointer;
            font-size: 12px;
            font-weight: 700;
        }

        .back-btn {
            background: #e5e7eb;
            color: #374151;
        }

        .print {
            background: #0b3d2e;
            color: white;
        }

        .cards {
            display: flex;
            justify-content: center;
            align-items: flex-start;
            gap: 12mm;
        }

        /*
        |--------------------------------------------------------------------------
        | Card size — standard CR80 ID card: 85.6mm x 54mm
        |--------------------------------------------------------------------------
        */

        .card {
            width: 85.6mm;
            height: 54mm;
            border-radius: 3mm;
            overflow: hidden;
            position: relative;
            background: #ffffff;
            border: 0.2mm solid #0b3d2e;
        }

        /* Decorative watermark seal, repeated as a subtle background pattern */
        .card::before {
            content: "";
            position: absolute;
            inset: 0;
            background-image:
                radial-gradient(circle at 88% 92%, rgba(212,175,55,0.08) 0, rgba(212,175,55,0.08) 18mm, transparent 18mm);
            pointer-events: none;
        }

        /* ============================================================
           FRONT
           ============================================================ */

        .front-header {
            height: 13mm;
            background: linear-gradient(120deg, #0b3d2e 0%, #145c46 55%, #0b3d2e 100%);
            padding: 1.8mm 2.5mm;
            display: flex;
            align-items: center;
            gap: 2mm;
            position: relative;
        }

        .front-header::after {
            content: "";
            position: absolute;
            left: 0;
            right: 0;
            bottom: -1.2mm;
            height: 1.2mm;
            background: linear-gradient(90deg, #d4af37, #f3dd8e 40%, #d4af37 100%);
        }

        .logo {
            width: 9mm;
            height: 9mm;
            object-fit: contain;
            background: white;
            border-radius: 50%;
            padding: 0.5mm;
        }

        .institution {
            font-size: 7.8px;
            line-height: 1.25;
            font-weight: 800;
            text-transform: uppercase;
            color: #ffffff;
            letter-spacing: 0.2px;
        }

        .address {
            margin-top: 0.6mm;
            font-size: 5.1px;
            color: #d7e5df;
            line-height: 1.3;
        }

        .id-title {
            position: absolute;
            right: 2.5mm;
            top: 2.2mm;
            background: rgba(212,175,55,0.95);
            color: #0b3d2e;
            font-size: 5.1px;
            font-weight: 800;
            padding: 1mm 1.8mm;
            border-radius: 6mm;
            letter-spacing: 0.3px;
        }

        /* Gap between the header and the body content */
        .front-body {
            position: absolute;
            top: 16.8mm;
            left: 2.5mm;
            right: 2.5mm;
            bottom: 2.3mm;
        }

        .front-field {
            margin-bottom: 1.6mm;
            padding-right: 21mm;
        }

        .front-label {
            font-size: 5.1px;
            font-weight: 700;
            color: #145c46;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }

        .front-line {
            margin-top: 0.5mm;
            border-bottom: 0.25mm solid #cbd5c9;
            min-height: 4mm;
            font-size: 7.6px;
            font-weight: 700;
            color: #0f172a;
            padding-bottom: 0.4mm;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .photo-box {
            position: absolute;
            right: 0;
            top: 0;
            width: 19mm;
            height: 22.5mm;
            border: 0.5mm solid #d4af37;
            border-radius: 1.3mm;
            overflow: hidden;
            background: #f1f5f4;
            box-shadow: 0 0.4mm 1mm rgba(0,0,0,0.18);
        }

        .photo-box img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .photo-ribbon {
            position: absolute;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(11,61,46,0.85);
            color: #fff;
            font-size: 4.2px;
            font-weight: 700;
            text-align: center;
            padding: 0.5mm 0;
            letter-spacing: 0.3px;
        }

        .front-footer {
            position: absolute;
            left: 0;
            right: 0;
            bottom: 0;
            display: flex;
            align-items: flex-end;
            justify-content: space-between;
        }

        .card-number {
            font-size: 4.9px;
            color: #374151;
        }

        .card-number strong {
            display: block;
            font-size: 6.3px;
            color: #0b3d2e;
            letter-spacing: 0.4px;
        }

        /* Moved to the right side of the footer */
        .issuing {
            font-size: 4.9px;
            line-height: 1.3;
            color: #374151;
            text-align: right;
        }

        .issuing strong {
            display: block;
            font-size: 5.6px;
            color: #0b3d2e;
        }

        /* ============================================================
           BACK
           ============================================================ */

        .back-header {
            height: 6.5mm;
            background: linear-gradient(120deg, #0b3d2e 0%, #145c46 55%, #0b3d2e 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
        }

        .back-header::after {
            content: "";
            position: absolute;
            left: 0;
            right: 0;
            bottom: -1.2mm;
            height: 1.2mm;
            background: linear-gradient(90deg, #d4af37, #f3dd8e 40%, #d4af37 100%);
        }

        .back-title {
            font-size: 7.2px;
            font-weight: 800;
            color: #ffffff;
            text-transform: uppercase;
            letter-spacing: 0.6px;
        }

        /* Gap between the header and the body content */
        .back-inner {
            padding: 4mm 3mm 0;
            position: relative;
        }

        .back-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            column-gap: 4mm;
            row-gap: 1.8mm;
            margin-right: 17mm;
        }

        .back-field {
            min-height: 5mm;
        }

        .back-label {
            display: block;
            font-size: 4.9px;
            font-weight: 700;
            color: #145c46;
            text-transform: uppercase;
            letter-spacing: 0.2px;
        }

        .back-value {
            display: block;
            min-width: 15mm;
            border-bottom: 0.25mm solid #cbd5c9;
            margin-top: 0.5mm;
            font-size: 6.4px;
            font-weight: 600;
            color: #0f172a;
            padding-bottom: 0.4mm;
        }

        .address-field {
            grid-column: 1 / -1;
        }

        .address-line {
            margin-top: 0.6mm;
            border-bottom: 0.25mm solid #cbd5c9;
            min-height: 4mm;
            font-size: 6.4px;
            color: #0f172a;
        }

        /* QR code block, pinned to the top-right of the back */
        .qr-box {
            position: absolute;
            right: 3mm;
            top: 4mm;
            width: 15mm;
            text-align: center;
        }

        .qr-box .qr-frame {
            width: 15mm;
            height: 15mm;
            padding: 0.6mm;
            background: #ffffff;
            border: 0.3mm solid #0b3d2e;
            border-radius: 1.3mm;
        }

        .qr-frame svg,
        .qr-frame img {
            width: 100%;
            height: 100%;
            display: block;
        }

        .qr-caption {
            margin-top: 0.8mm;
            font-size: 4px;
            font-weight: 700;
            color: #145c46;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }

        .note {
            position: absolute;
            left: 3mm;
            right: 3mm;
            bottom: 2.2mm;
            background: #0b3d2e;
            color: #f3f4f6;
            padding: 1.6mm 2.2mm;
            text-align: center;
            font-size: 4.6px;
            line-height: 1.4;
            border-radius: 1mm;
            border-left: 0.8mm solid #d4af37;
        }

        .note strong {
            font-size: 5px;
            color: #f3dd8e;
        }

        @media print {

            .toolbar {
                display: none;
            }

            .cards {
                gap: 12mm;
            }

            body {
                background: white;
            }

            .card {
                box-shadow: none;
            }

            /*
             * Force browsers to print background colors, gradients and
             * shadows instead of stripping them out to save ink.
             * -webkit- prefix covers Chrome / Edge / Safari.
             * Firefox still needs "Print backgrounds" checked manually
             * in its print dialog — there is no pure-CSS override for it.
             */
            *,
            *::before,
            *::after {
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
                color-adjust: exact !important;
            }

        }

        @media screen {

            body {
                padding: 20px;
                background: #e5e7eb;
            }

            .card {
                box-shadow: 0 6px 24px rgba(0,0,0,.16);
            }

        }

    </style>

</head>


<body>


{{-- Toolbar --}}
<div class="toolbar">

    <div>

        <div class="toolbar-title">
            Student Card
        </div>

        <div class="toolbar-subtitle">
            {{ $studentCard->card_no }}
        </div>

    </div>


    <div style="display:flex;gap:8px;">

        <a
            href="{{ route(
                'admin.admissions.show',
                $admission
            ) }}"
            class="back-btn">

            Back

        </a>


        <button
            type="button"
            class="print"
            onclick="window.print()">

            Print Card

        </button>

    </div>

</div>


<div class="cards">


    {{-- ========================================================= --}}
    {{-- FRONT                                                     --}}
    {{-- ========================================================= --}}

    <div class="card">

        <div class="id-title">
            TRAINEE ID
        </div>


        <div class="front-header">

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
                    Govt. Advance Technical
                    Training Centre (GATTC)
                </div>

                <div class="address">
                    16-A Industrial Estate, Hayatabad, Peshawar
                    &nbsp;|&nbsp; Ph: 091-5881389
                </div>

            </div>

            @if(
                file_exists(
                    public_path(
                        'images/tevta-logo.png'
                    )
                )
            )

                <img
                    src="{{ asset(
                        'images/tevta-logo.png'
                    ) }}"
                    class="logo"
                    alt="TEVTA">

            @endif


        </div>


        <div class="front-body">


            {{-- Photo --}}
            <div class="photo-box">

                @if($studentCard->photo)

                    <img
                        src="{{ asset(
                            'storage/' .
                            $studentCard->photo
                        ) }}"
                        alt="Student Photo">

                @endif

                <div class="photo-ribbon">
                    TRAINEE
                </div>

            </div>


            {{-- Name --}}
            <div class="front-field">

                <div class="front-label">
                    Name
                </div>

                <div class="front-line">
                    {{ $admission->student_name }}
                </div>

            </div>


            {{-- Father --}}
            <div class="front-field">

                <div class="front-label">
                    Father Name
                </div>

                <div class="front-line">
                    {{ $admission->father_name }}
                </div>

            </div>


            {{-- CNIC --}}
            <div class="front-field">

                <div class="front-label">
                    CNIC
                </div>

                <div class="front-line">
                    {{ $admission->cnic }}
                </div>

            </div>


            {{-- Trade --}}
            <div class="front-field">

                <div class="front-label">
                    Trade
                </div>

                <div class="front-line">
                    {{ $course?->title }}
                </div>

            </div>


            {{-- Footer: card number (left) + issuing authority (right) --}}
            <div class="front-footer">

                <div class="card-number">
                    Card No.
                    <strong>
                        {{ $studentCard->card_no }}
                    </strong>
                </div>

                <div class="issuing">
                    Issuing Authority
                    <strong>
                        Principal, GATTC
                    </strong>
                </div>

            </div>

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- BACK                                                      --}}
    {{-- ========================================================= --}}

    <div class="card">

        <div class="back-header">

            <div class="back-title">
                Trainee Information
            </div>

        </div>


        <div class="back-inner">


            {{-- Security QR code --}}
            <div class="qr-box">

                <div class="qr-frame">
                    {{--
                        Requires: composer require bacon/bacon-qr-code
                        Rendered as inline SVG above (no ext-gd needed),
                        so the card stays fully self-contained and print-safe.
                    --}}
                    {!! $qrSvg !!}
                </div>

                <div class="qr-caption">
                    Scan to Verify
                </div>

            </div>


            <div class="back-grid">


                {{-- Boarding --}}
                <div class="back-field">
                    <span class="back-label">Boarding</span>
                    <span class="back-value">No</span>
                </div>


                {{-- Hostel --}}
                <div class="back-field">
                    <span class="back-label">Hostel / Room No.</span>
                    <span class="back-value">—</span>
                </div>


                {{-- DOB --}}
                <div class="back-field">
                    <span class="back-label">D.O.B</span>
                    <span class="back-value">
                        {{ optional(
                            $admission->date_of_birth
                        )->format('d-m-Y') ?: '—' }}
                    </span>
                </div>


                {{-- Blood Group --}}
                <div class="back-field">
                    <span class="back-label">Blood Group</span>
                    <span class="back-value">—</span>
                </div>


                {{-- Contact --}}
                <div class="back-field">
                    <span class="back-label">Contact</span>
                    <span class="back-value">
                        {{ $admission->phone }}
                    </span>
                </div>


                {{-- Issue --}}
                <div class="back-field">
                    <span class="back-label">Issue Date</span>
                    <span class="back-value">
                        {{ optional(
                            $studentCard->issued_at
                        )->format('d-m-Y') }}
                    </span>
                </div>


                {{-- Expiry --}}
                <div class="back-field">
                    <span class="back-label">Expire Date</span>
                    <span class="back-value">
                        {{ optional(
                            $studentCard->expiry_date
                        )->format('d-m-Y') }}
                    </span>
                </div>


                {{-- Admission --}}
                <div class="back-field">
                    <span class="back-label">Admission No.</span>
                    <span class="back-value">
                        {{ $admission->admission_no }}
                    </span>
                </div>


                {{-- Address --}}
                <div class="back-field address-field">
                    <span class="back-label">Address</span>
                    <div class="address-line">
                        {{ $admission->address }}
                    </div>
                </div>

            </div>

        </div>


        <div class="note">

            <strong>
                Note:
            </strong>

            This is a computer-generated card and does not require a
            signature — authenticity can be verified via the QR code
            above. If found, return to GATTC, Hayatabad, Peshawar.

        </div>

    </div>

</div>


</body>

</html>