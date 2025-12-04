<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Diploma - {{ $participant->name }}</title>
    <style>
        @page {
            margin: 0;
            size: landscape;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'DejaVu Sans', sans-serif;
            background: #fff;
            width: 100%;
            height: 100%;
        }
        
        .diploma {
            width: 100%;
            min-height: 100vh;
            padding: 30px;
            position: relative;
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        }
        
        .border-frame {
            border: 8px double #1E3A5F;
            padding: 40px;
            height: 100%;
            position: relative;
        }
        
        .corner-decoration {
            position: absolute;
            width: 60px;
            height: 60px;
            border: 3px solid #F97316;
        }
        
        .corner-tl { top: 10px; left: 10px; border-right: none; border-bottom: none; }
        .corner-tr { top: 10px; right: 10px; border-left: none; border-bottom: none; }
        .corner-bl { bottom: 10px; left: 10px; border-right: none; border-top: none; }
        .corner-br { bottom: 10px; right: 10px; border-left: none; border-top: none; }
        
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        
        .logo-section {
            display: table;
            width: 100%;
            margin-bottom: 10px;
        }
        
        .logo-left, .logo-right {
            display: table-cell;
            width: 15%;
            vertical-align: middle;
            text-align: center;
        }
        
        .logo-center {
            display: table-cell;
            width: 70%;
            text-align: center;
            vertical-align: middle;
        }
        
        .institution {
            font-size: 11px;
            color: #1E3A5F;
            text-transform: uppercase;
            letter-spacing: 3px;
            font-weight: bold;
        }
        
        .campus {
            font-size: 13px;
            color: #F97316;
            font-weight: bold;
            margin-top: 3px;
        }
        
        .diploma-title {
            text-align: center;
            margin: 25px 0;
        }
        
        .diploma-title h1 {
            font-size: 42px;
            color: #1E3A5F;
            text-transform: uppercase;
            letter-spacing: 8px;
            font-weight: bold;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.1);
        }
        
        .subtitle {
            font-size: 14px;
            color: #666;
            margin-top: 5px;
            letter-spacing: 2px;
        }
        
        .content {
            text-align: center;
            margin: 30px 0;
        }
        
        .otorgado {
            font-size: 14px;
            color: #666;
            margin-bottom: 15px;
        }
        
        .participant-name {
            font-size: 32px;
            color: #1E3A5F;
            font-weight: bold;
            padding: 10px 0;
            border-bottom: 2px solid #F97316;
            display: inline-block;
            min-width: 400px;
        }
        
        .recognition-text {
            font-size: 14px;
            color: #444;
            margin: 25px auto;
            max-width: 600px;
            line-height: 1.8;
        }
        
        .event-name {
            font-size: 18px;
            color: #F97316;
            font-weight: bold;
            margin: 15px 0;
        }
        
        .project-info {
            background: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 8px;
            padding: 15px 30px;
            display: inline-block;
            margin: 15px 0;
        }
        
        .project-label {
            font-size: 10px;
            color: #666;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        
        .project-name {
            font-size: 16px;
            color: #1E3A5F;
            font-weight: bold;
        }
        
        .team-name {
            font-size: 12px;
            color: #888;
            margin-top: 5px;
        }
        
        .award-section {
            margin: 20px 0;
            text-align: center;
        }
        
        .medal {
            font-size: 48px;
            display: block;
            margin-bottom: 5px;
        }
        
        .award-category {
            font-size: 20px;
            color: #1E3A5F;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 2px;
        }
        
        .signatures {
            display: table;
            width: 100%;
            margin-top: 40px;
        }
        
        .signature-box {
            display: table-cell;
            width: 33.33%;
            text-align: center;
            padding: 0 20px;
        }
        
        .signature-line {
            border-top: 1px solid #333;
            margin: 0 auto;
            width: 200px;
            padding-top: 8px;
        }
        
        .signature-name {
            font-size: 11px;
            color: #333;
            font-weight: bold;
        }
        
        .signature-title {
            font-size: 9px;
            color: #666;
        }
        
        .footer {
            text-align: center;
            margin-top: 20px;
        }
        
        .date-location {
            font-size: 11px;
            color: #666;
        }
        
        .folio {
            font-size: 9px;
            color: #999;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div class="diploma">
        <div class="border-frame">
            <div class="corner-decoration corner-tl"></div>
            <div class="corner-decoration corner-tr"></div>
            <div class="corner-decoration corner-bl"></div>
            <div class="corner-decoration corner-br"></div>
            
            <div class="header">
                <div class="logo-section">
                    <div class="logo-left">
                        {{-- Logo TecNM --}}
                        <div style="font-size: 24px;">🎓</div>
                    </div>
                    <div class="logo-center">
                        <div class="institution">Tecnológico Nacional de México</div>
                        <div class="campus">Instituto Tecnológico de Oaxaca</div>
                    </div>
                    <div class="logo-right">
                        {{-- Logo ITO --}}
                        <div style="font-size: 24px;">🏛️</div>
                    </div>
                </div>
            </div>
            
            <div class="diploma-title">
                <h1>Diploma</h1>
                <p class="subtitle">de {{ $type === 'participation' ? 'Participación' : 'Reconocimiento' }}</p>
            </div>
            
            <div class="content">
                <p class="otorgado">Se otorga el presente diploma a:</p>
                
                <div class="participant-name">{{ $participant->name }}</div>
                
                <p class="recognition-text">
                    @if($type === 'winner' && isset($award))
                        Por haber obtenido el <strong>{{ $award->category }}</strong> 
                        en el evento académico:
                    @else
                        Por su destacada participación en el evento académico:
                    @endif
                </p>
                
                <div class="event-name">« {{ $event->name }} »</div>
                
                @if(isset($project))
                    <div class="project-info">
                        <div class="project-label">Proyecto presentado</div>
                        <div class="project-name">{{ $project->title }}</div>
                        <div class="team-name">Equipo: {{ $team->name }}</div>
                    </div>
                @endif
                
                @if($type === 'winner' && isset($award))
                    <div class="award-section">
                        <span class="medal">
                            @if(Str::contains(strtolower($award->category), ['oro', 'primer', '1er', '1°']))
                                🥇
                            @elseif(Str::contains(strtolower($award->category), ['plata', 'segundo', '2do', '2°']))
                                🥈
                            @elseif(Str::contains(strtolower($award->category), ['bronce', 'tercer', '3er', '3°']))
                                🥉
                            @else
                                🏆
                            @endif
                        </span>
                        <div class="award-category">{{ $award->category }}</div>
                    </div>
                @endif
            </div>
            
            <div class="signatures">
                <div class="signature-box">
                    <div class="signature-line">
                        <div class="signature-name">Dr. José Luis García Martínez</div>
                        <div class="signature-title">Director del Instituto</div>
                    </div>
                </div>
                <div class="signature-box">
                    <div class="signature-line">
                        <div class="signature-name">Comité Organizador</div>
                        <div class="signature-title">{{ $event->name }}</div>
                    </div>
                </div>
                <div class="signature-box">
                    <div class="signature-line">
                        <div class="signature-name">Ing. María Fernanda López</div>
                        <div class="signature-title">Jefa del Depto. de Vinculación</div>
                    </div>
                </div>
            </div>
            
            <div class="footer">
                <p class="date-location">
                    Oaxaca de Juárez, Oaxaca, a {{ $event->end_date->format('d') }} de {{ $event->end_date->translatedFormat('F') }} de {{ $event->end_date->format('Y') }}
                </p>
                <p class="folio">
                    Folio: {{ strtoupper(substr(md5($participant->id . $event->id . ($award->id ?? 0)), 0, 8)) }}-{{ $event->id }}
                </p>
            </div>
        </div>
    </div>
</body>
</html>
