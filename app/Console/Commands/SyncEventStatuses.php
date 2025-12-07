<?php

namespace App\Console\Commands;

use App\Models\Event;
use Illuminate\Console\Command;

class SyncEventStatuses extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'events:sync-status';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sincroniza el estado de los eventos basándose en las fechas configuradas';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🔄 Sincronizando estados de eventos...');
        
        $events = Event::whereIn('status', [Event::STATUS_REGISTRATION, Event::STATUS_ACTIVE])->get();
        
        $updated = 0;
        
        foreach ($events as $event) {
            $oldStatus = $event->status;
            
            if ($event->syncStatus()) {
                $updated++;
                $this->line("  ✅ {$event->name}: {$oldStatus} → {$event->status}");
            }
        }
        
        if ($updated > 0) {
            $this->info("✨ Se actualizaron {$updated} evento(s).");
        } else {
            $this->info("ℹ️  No hubo cambios de estado.");
        }
        
        return Command::SUCCESS;
    }
}
