<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Diploma</title>
    <style>
        @page {
            size: A4 landscape;
            margin: 0;
        }
        
        body {
            margin: 0;
            padding: 0;
            font-family: "DejaVu Sans", sans-serif;
            width: 100%;
            height: 100%;
        }

        .page-container {
            position: relative;
            width: 297mm;
            height: 210mm;
            overflow: hidden;
        }

        .border-frame {
            position: absolute;
            top: 10mm;
            left: 10mm;
            width: 267mm;
            height: 180mm;
            border: 3px double #1E3A5F;
            padding: 5mm;
            box-sizing: border-box;
        }

        .corner {
            position: absolute;
            width: 20px;
            height: 20px;
            border: 2px solid #F97316;
            z-index: 10;
        }
        .corner-tl { top: -2px; left: -2px; border-right: none; border-bottom: none; }
        .corner-tr { top: -2px; right: -2px; border-left: none; border-bottom: none; }
        .corner-bl { bottom: -2px; left: -2px; border-right: none; border-top: none; }
        .corner-br { bottom: -2px; right: -2px; border-left: none; border-top: none; }

        .main-table {
            width: 100%;
            height: 100%;
            border-collapse: collapse;
            table-layout: fixed;
        }

        .main-table td {
            text-align: center;
            vertical-align: middle;
        }

        .header-row { height: 15%; }
        
        .logo-img {
            width: 80px;
            height: auto;
        }

        .institution-text {
            font-size: 12px;
            color: #1E3A5F;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-weight: bold;
            line-height: 1.4;
        }

        .campus-text {
            font-size: 14px;
            color: #F97316;
            font-weight: bold;
            margin-top: 5px;
        }

        .title-row { height: 15%; }

        .diploma-title {
            font-size: 42px;
            color: #1E3A5F;
            text-transform: uppercase;
            letter-spacing: 8px;
            font-weight: bold;
            margin: 0;
            line-height: 1;
        }

        .diploma-subtitle {
            font-size: 14px;
            color: #666;
            letter-spacing: 3px;
            margin-top: 5px;
            text-transform: uppercase;
        }

        .participant-row { height: 20%; }

        .otorga-text {
            font-size: 12px;
            color: #555;
            margin-bottom: 10px;
        }

        .participant-name {
            font-size: 32px;
            color: #1E3A5F;
            font-weight: bold;
            border-bottom: 2px solid #F97316;
            display: inline-block;
            padding: 0 30px 5px 30px;
            margin: 0;
        }

        .details-row {
            height: 25%;
            vertical-align: top !important;
            padding-top: 10px;
        }

        .recognition-text {
            font-size: 13px;
            color: #444;
            margin-bottom: 8px;
            line-height: 1.5;
        }

        .event-name {
            font-size: 20px;
            color: #F97316;
            font-weight: bold;
        }

        .project-container {
            margin-top: 15px;
            background-color: #f8f9fa;
            border: 1px solid #e9ecef;
            border-radius: 8px;
            padding: 10px 20px;
            display: inline-block;
        }

        .project-label {
            font-size: 10px;
            color: #888;
            text-transform: uppercase;
            letter-spacing: 1px;
            display: block;
            margin-bottom: 3px;
        }

        .project-name {
            font-size: 16px;
            color: #1E3A5F;
            font-weight: bold;
            display: block;
        }

        .team-name {
            font-size: 12px;
            color: #666;
            margin-top: 3px;
            display: block;
        }

        .winner-badge { margin-top: 10px; }
        .winner-text {
            font-size: 18px;
            color: #1E3A5F;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .signatures-row {
            height: 25%;
            vertical-align: bottom !important;
        }

        .signatures-table {
            width: 100%;
            margin-bottom: 10px;
        }

        .signature-cell {
            width: 33.33%;
            text-align: center;
            vertical-align: bottom;
            padding: 0 10px;
        }

        .signature-line {
            border-top: 1px solid #999;
            width: 80%;
            margin: 0 auto 8px auto;
        }

        .signer-name {
            font-size: 11px;
            font-weight: bold;
            color: #333;
            margin-bottom: 2px;
        }

        .signer-title {
            font-size: 9px;
            color: #666;
        }
        
        .date-text {
            font-size: 9px;
            color: #999;
            margin-top: 5px;
        }
    </style>
</head>
<body>
    <div class="page-container">
        <div class="border-frame">
            <div class="corner corner-tl"></div>
            <div class="corner corner-tr"></div>
            <div class="corner corner-bl"></div>
            <div class="corner corner-br"></div>

            <table class="main-table">
                <tr class="header-row">
                    <td style="width: 15%;">
                        <img src="{{ public_path('img/logo-tecnm.png') }}" class="logo-img" alt="TecNM">
                    </td>
                    <td style="width: 70%;">
                        <div class="institution-text">Tecnológico Nacional de México</div>
                        <div class="institution-text">Instituto Tecnológico de Oaxaca</div>
                    </td>
                    <td style="width: 15%;">
                        <img src="{{ public_path('img/logo-ito.png') }}" class="logo-img" alt="ITO">
                    </td>
                </tr>

                <tr class="title-row">
                    <td colspan="3">
                        <h1 class="diploma-title">DIPLOMA</h1>
                        <div class="diploma-subtitle">
                            @if($type === 'winner')
                                DE GANADOR
                            @else
                                DE PARTICIPACIÓN
                            @endif
                        </div>
                    </td>
                </tr>

                <tr class="participant-row">
                    <td colspan="3">
                        <div class="otorga-text">Se otorga el presente diploma a:</div>
                        <div class="participant-name">{{ $participant->name }}</div>
                    </td>
                </tr>

                <tr class="details-row">
                    <td colspan="3">
                        <div class="recognition-text">
                            Por su destacada participación en el evento académico:
                        </div>
                        <div class="event-name"> {{ $event->name }} </div>

                        @if($type === 'winner' && isset($award))
                            <div class="winner-badge">
                                <div class="winner-text"> {{ $award->category }}</div>
                            </div>
                        @endif

                        @if(isset($project))
                            <div class="project-container">
                                <span class="project-label">Proyecto Presentado</span>
                                <span class="project-name">{{ $project->title }}</span>
                                <span class="team-name">Equipo: {{ $team->name }}</span>
                            </div>
                        @endif
                    </td>
                </tr>

                <tr class="signatures-row">
                    <td colspan="3">
                        <table class="signatures-table">
                            <tr>
                                <td class="signature-cell">
                                    <div style="height: 40px;"></div>
                                    <div class="signature-line"></div>
                                    <div class="signer-name">Dr. Jesús Martínez García</div>
                                    <div class="signer-title">Director del Instituto</div>
                                </td>
                                <td class="signature-cell">
                                    <div class="date-text">
                                        Oaxaca de Juárez, Oaxaca, a {{ \Carbon\Carbon::now()->isoFormat('D [de] MMMM [de] YYYY') }}
                                        <br>
                                        <span style="font-size: 7px; color: #ccc;">Folio: {{ substr(md5($participant->id . $event->id), 0, 8) }}</span>
                                    </div>
                                </td>
                                <td class="signature-cell">
                                    <div style="height: 40px;"></div>
                                    <div class="signature-line"></div>
                                    <div class="signer-name">Ing. María Fernanda López</div>
                                    <div class="signer-title">Jefa del Depto. de Sistemas</div>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </div>
    </div>
</body>
</html>
