<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Award;
use Illuminate\Http\Request;

class PublicController extends Controller
{
    /**
     * Muestra la página pública de ganadores
     */
    public function winners()
    {
        // Obtener eventos cerrados que tienen premios
        $eventsWithAwards = Event::where('is_active', false)
            ->whereHas('awards')
            ->with(['awards.team.project', 'awards.team.members', 'awards.team.leader'])
            ->orderBy('end_date', 'desc')
            ->get();

        return view('public.winners', compact('eventsWithAwards'));
    }

    /**
     * Muestra los ganadores de un evento específico
     */
    public function eventWinners(Event $event)
    {
        // Solo mostrar si el evento está cerrado
        if ($event->is_active) {
            abort(404, 'Los resultados aún no están disponibles.');
        }

        $event->load(['awards.team.project', 'awards.team.members', 'awards.team.leader']);

        // Ordenar premios por categoría (1er, 2do, 3er lugar primero)
        $awards = $event->awards->sortBy(function ($award) {
            $order = [
                '1er Lugar' => 1,
                '2do Lugar' => 2,
                '3er Lugar' => 3,
                'Mención Honorífica' => 4,
                'Mejor Innovación' => 5,
                'Mejor Diseño' => 6,
                'Mejor Presentación' => 7,
                'Premio del Público' => 8,
                'Otro' => 9,
            ];
            return $order[$award->category] ?? 10;
        });

        return view('public.event-winners', compact('event', 'awards'));
    }
}
