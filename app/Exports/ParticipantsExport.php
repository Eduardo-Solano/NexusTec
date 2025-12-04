<?php

namespace App\Exports;

use App\Models\Event;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ParticipantsExport implements FromCollection, WithHeadings, WithMapping, WithStyles, ShouldAutoSize
{
    protected Event $event;

    public function __construct(Event $event)
    {
        $this->event = $event;
    }

    public function collection()
    {
        return $this->event->teams()
            ->with(['members.studentProfile.career', 'project', 'leader'])
            ->get()
            ->flatMap(function ($team) {
                return $team->members->map(function ($member) use ($team) {
                    return (object) [
                        'team' => $team,
                        'member' => $member,
                    ];
                });
            });
    }

    public function headings(): array
    {
        return [
            '#',
            'Equipo',
            'Rol en Equipo',
            'Nombre del Participante',
            'Email',
            'No. Control',
            'Carrera',
            'Semestre',
            'Proyecto',
            'Fecha de Inscripción',
        ];
    }

    public function map($row): array
    {
        static $index = 0;
        $index++;

        $member = $row->member;
        $team = $row->team;
        $profile = $member->studentProfile;

        return [
            $index,
            $team->name,
            $member->id === $team->leader_id ? 'Líder' : ($member->pivot->role ?? 'Miembro'),
            $member->name,
            $member->email,
            $profile?->control_number ?? 'N/A',
            $profile?->career?->name ?? 'N/A',
            $profile?->semester ?? 'N/A',
            $team->project?->name ?? 'Sin proyecto',
            $team->created_at->format('d/m/Y'),
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => [
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '1B3A57'],
                ],
            ],
        ];
    }
}
