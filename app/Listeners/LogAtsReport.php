<?php

namespace App\Listeners;

use App\Events\AtsReportGenerated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;

class LogAtsReport implements ShouldQueue
{
    public function handle(AtsReportGenerated $event): void
    {
        Log::info('ATS report delivered', [
            'correlation_id' => $event->correlationId,
            'score' => $event->report['atsScore'] ?? null,
            'warnings' => count($event->report['warnings'] ?? []),
        ]);
    }
}

