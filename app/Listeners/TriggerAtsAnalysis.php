<?php

namespace App\Listeners;

use App\Events\AtsReportGenerated;
use App\Events\CvGenerated;
use App\Services\AtsCheckerService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;

class TriggerAtsAnalysis implements ShouldQueue
{
    public function __construct(
        protected AtsCheckerService $atsCheckerService,
    ) {
    }

    public function handle(CvGenerated $event): void
    {
        if ($event->analysisMode !== 'async') {
            return;
        }

        $report = $this->atsCheckerService->analyze($event->cvPayload);

        Log::info('ATS report generated asynchronously', [
            'correlation_id' => $event->correlationId,
            'score' => $report['atsScore'] ?? null,
        ]);

        AtsReportGenerated::dispatch($event->correlationId, $report);
    }
}

