<?php

namespace App\Services\Gateway;

use App\Events\AtsReportGenerated;
use App\Events\CvGenerated;
use App\Services\AtsCheckerService;
use App\Services\CvGeneratorService;
use App\Services\Support\SupportBotService;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class TalentPlatformGateway
{
    public function __construct(
        private readonly CvGeneratorService $cvGeneratorService,
        private readonly AtsCheckerService $atsCheckerService,
        private readonly SupportBotService $supportBotService,
    ) {
    }

    public function generateCv(
        array $cvPayload,
        string $template,
        string $analysisMode = 'async',
        ?string $correlationId = null,
    ): array {
        $correlationId = $correlationId ?? (string) Str::uuid();

        $documentPath = $this->cvGeneratorService->generate($cvPayload, $template);
        $documentUrl = Storage::disk('public')->url($documentPath);

        CvGenerated::dispatch($correlationId, $cvPayload, $documentPath, $analysisMode);

        $response = [
            'correlation_id' => $correlationId,
            'document_path' => $documentPath,
            'document_url' => $documentUrl,
            'analysis_mode' => $analysisMode,
        ];

        if ($analysisMode === 'sync') {
            $report = $this->atsCheckerService->analyze($cvPayload);
            AtsReportGenerated::dispatch($correlationId, $report);
            $response['ats_report'] = $report;
        }

        return $response;
    }

    public function analyzeCv(array $cvPayload, ?string $correlationId = null, bool $dispatchEvent = true): array
    {
        $correlationId = $correlationId ?? (string) Str::uuid();
        $report = $this->atsCheckerService->analyze($cvPayload);

        if ($dispatchEvent) {
            AtsReportGenerated::dispatch($correlationId, $report);
        }

        return [
            'correlation_id' => $correlationId,
            'report' => $report,
        ];
    }

    public function applyAtsFixes(array $cvPayload, array $fixes): array
    {
        return $this->atsCheckerService->applyFixes($cvPayload, $fixes);
    }

    public function startSupportSession(?string $persona = null): array
    {
        return $this->supportBotService->startSession($persona);
    }

    public function progressSupportSession(string $sessionId, string $optionId): array
    {
        return $this->supportBotService->progress($sessionId, $optionId);
    }
}
