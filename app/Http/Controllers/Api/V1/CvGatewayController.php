<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Services\Gateway\TalentPlatformGateway;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CvGatewayController extends Controller
{
    public function __construct(
        private readonly TalentPlatformGateway $gateway,
    ) {
    }

    public function generate(Request $request): JsonResponse
    {
        $payload = $request->validate([
            'cv' => 'required|array',
            'cv.name' => 'required|string|max:255',
            'cv.email' => 'required|email',
            'cv.about' => 'nullable|string',
            'template' => 'required|string|in:Minimal,Business,Tech',
            'analysis_mode' => 'nullable|string|in:none,sync,async',
            'correlation_id' => 'nullable|string',
        ]);

        $analysisMode = $payload['analysis_mode'] ?? 'async';
        $correlationId = $payload['correlation_id'] ?? null;

        $result = $this->gateway->generateCv(
            $payload['cv'],
            $payload['template'],
            $analysisMode,
            $correlationId,
        );

        return response()->json($result, 201);
    }

    public function applyFixes(Request $request): JsonResponse
    {
        $data = $request->validate([
            'cv' => 'required|array',
            'fixes' => 'required|array',
        ]);

        $updated = $this->gateway->applyAtsFixes($data['cv'], $data['fixes']);

        return response()->json([
            'cv' => $updated,
        ]);
    }
}
