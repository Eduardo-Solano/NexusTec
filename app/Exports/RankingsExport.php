<?php

namespace App\Exports;

use App\Models\Event;
use App\Models\Project;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class RankingsExport implements FromCollection, WithHeadings, WithMapping, WithTitle, WithStyles, ShouldAutoSize
{
    protected $event;
    protected $rankedProjects;
    protected $criteria;
    protected $position = 0;

    public function __construct(Event $event, $rankedProjects, $criteria)
    {
        $this->event = $event;
        $this->rankedProjects = $rankedProjects;
        $this->criteria = $criteria;
    }

    public function collection()
    {
        return $this->rankedProjects;
    }

    public function headings(): array
    {
        $headers = [
            '#',
            'Equipo',
            'Proyecto',
            'Líder',
        ];

        // Agregar columnas para cada criterio
        foreach ($this->criteria as $criterion) {
            $headers[] = $criterion->name . ' (máx ' . $criterion->max_points . ')';
        }

        $headers[] = 'Puntaje Total';
        $headers[] = 'Máximo Posible';
        $headers[] = 'Porcentaje';
        $headers[] = 'Jueces Completados';
        $headers[] = 'Estado';

        return $headers;
    }

    public function map($row): array
    {
        $this->position++;
        
        $data = [
            $this->position,
            $row['team']->name,
            $row['project']->title,
            $row['team']->leader->name ?? 'N/A',
        ];

        // Agregar puntajes por criterio
        foreach ($this->criteria as $criterion) {
            $score = $row['scores_by_criterion'][$criterion->id]['average'] ?? '-';
            $data[] = $score !== null ? $score : '-';
        }

        $data[] = round($row['total_score'], 2);
        $data[] = $row['max_possible'];
        $data[] = $row['percentage'] . '%';
        $data[] = $row['judges_completed'] . '/' . $row['judges_total'];
        $data[] = $row['is_fully_evaluated'] ? 'Completo' : 'Pendiente';

        return $data;
    }

    public function title(): string
    {
        return 'Rankings';
    }

    public function styles(Worksheet $sheet)
    {
        // Estilo para el encabezado
        $lastColumn = $sheet->getHighestColumn();
        
        return [
            1 => [
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '1E3A5F'], // TecNM Blue
                ],
            ],
        ];
    }
}
