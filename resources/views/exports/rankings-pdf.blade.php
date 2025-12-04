<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Rankings - {{ $event->name }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 10px;
            color: #333;
            line-height: 1.4;
        }
        
        .header {
            text-align: center;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 3px solid #1E3A5F;
        }
        
        .header h1 {
            font-size: 18px;
            color: #1E3A5F;
            margin-bottom: 5px;
        }
        
        .header h2 {
            font-size: 14px;
            color: #F97316;
            margin-bottom: 8px;
        }
        
        .header p {
            font-size: 10px;
            color: #666;
        }
        
        .stats-grid {
            display: table;
            width: 100%;
            margin-bottom: 20px;
        }
        
        .stat-box {
            display: table-cell;
            width: 25%;
            text-align: center;
            padding: 10px;
            background: #f8f9fa;
            border: 1px solid #dee2e6;
        }
        
        .stat-box .number {
            font-size: 16px;
            font-weight: bold;
            color: #1E3A5F;
        }
        
        .stat-box .label {
            font-size: 8px;
            color: #666;
            text-transform: uppercase;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        
        th {
            background: #1E3A5F;
            color: white;
            padding: 8px 5px;
            text-align: left;
            font-size: 9px;
            font-weight: bold;
        }
        
        td {
            padding: 6px 5px;
            border-bottom: 1px solid #dee2e6;
            font-size: 9px;
        }
        
        tr:nth-child(even) {
            background: #f8f9fa;
        }
        
        .position {
            font-weight: bold;
            text-align: center;
            width: 30px;
        }
        
        .gold { color: #FFD700; }
        .silver { color: #C0C0C0; }
        .bronze { color: #CD7F32; }
        
        .score {
            text-align: center;
            font-weight: bold;
        }
        
        .percentage {
            text-align: center;
        }
        
        .status-complete {
            color: #059669;
            font-weight: bold;
        }
        
        .status-pending {
            color: #D97706;
        }
        
        .footer {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            text-align: center;
            font-size: 8px;
            color: #666;
            padding: 10px;
            border-top: 1px solid #dee2e6;
        }
        
        .page-break {
            page-break-after: always;
        }
        
        .medal {
            font-size: 12px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>🏆 RANKINGS OFICIALES</h1>
        <h2>{{ $event->name }}</h2>
        <p>
            📅 {{ $event->start_date->format('d/m/Y') }} - {{ $event->end_date->format('d/m/Y') }}
            &nbsp;&nbsp;|&nbsp;&nbsp;
            📄 Generado: {{ now()->format('d/m/Y H:i') }}
        </p>
    </div>

    <div class="stats-grid">
        <div class="stat-box">
            <div class="number">{{ $stats['total_projects'] }}</div>
            <div class="label">Proyectos Totales</div>
        </div>
        <div class="stat-box">
            <div class="number">{{ $stats['fully_evaluated'] }}</div>
            <div class="label">Evaluados</div>
        </div>
        <div class="stat-box">
            <div class="number">{{ $stats['pending_evaluation'] }}</div>
            <div class="label">Pendientes</div>
        </div>
        <div class="stat-box">
            <div class="number">{{ number_format($stats['average_score'], 1) }}%</div>
            <div class="label">Promedio General</div>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 30px;">#</th>
                <th style="width: 120px;">Equipo</th>
                <th style="width: 150px;">Proyecto</th>
                <th style="width: 100px;">Líder</th>
                @foreach($criteria as $criterion)
                    <th style="text-align: center;">{{ Str::limit($criterion->name, 15) }}</th>
                @endforeach
                <th style="text-align: center; width: 50px;">Total</th>
                <th style="text-align: center; width: 40px;">%</th>
                <th style="text-align: center; width: 50px;">Estado</th>
            </tr>
        </thead>
        <tbody>
            @foreach($rankedProjects as $index => $row)
                <tr>
                    <td class="position">
                        @if($index === 0)
                            <span class="medal">🥇</span>
                        @elseif($index === 1)
                            <span class="medal">🥈</span>
                        @elseif($index === 2)
                            <span class="medal">🥉</span>
                        @else
                            {{ $index + 1 }}
                        @endif
                    </td>
                    <td><strong>{{ Str::limit($row['team']->name, 20) }}</strong></td>
                    <td>{{ Str::limit($row['project']->title, 25) }}</td>
                    <td>{{ Str::limit($row['team']->leader->name ?? 'N/A', 18) }}</td>
                    @foreach($criteria as $criterion)
                        <td class="score">
                            {{ $row['scores_by_criterion'][$criterion->id]['average'] ?? '-' }}
                        </td>
                    @endforeach
                    <td class="score">{{ round($row['total_score'], 2) }}</td>
                    <td class="percentage">{{ $row['percentage'] }}%</td>
                    <td style="text-align: center;">
                        @if($row['is_fully_evaluated'])
                            <span class="status-complete">✓</span>
                        @else
                            <span class="status-pending">⏳</span>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    @if($rankedProjects->count() === 0)
        <div style="text-align: center; padding: 40px; color: #666;">
            <p>No hay proyectos registrados para este evento.</p>
        </div>
    @endif

    <div class="footer">
        <p>
            Instituto Tecnológico de Oaxaca — TecNM | NexusTec — Sistema de Gestión de Eventos Académicos
            <br>
            Documento generado automáticamente. Página 1
        </p>
    </div>
</body>
</html>
