<?php

namespace App\Traits;

use App\Models\ActivityLog;

trait LogsActivity
{
    /**
     * Registrar una actividad
     */
    protected function logActivity(string $action, string $description, $model = null, array $properties = []): ActivityLog
    {
        return ActivityLog::log($action, $description, $model, $properties);
    }
}
