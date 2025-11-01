<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Services\Gateway\TalentPlatformGateway;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AtsGatewayController extends Controller
{
    public function __construct(
        private readonly TalentPlatformGateway $gateway,
    ) {
    }

    public function analyze(Request $request): JsonResponse
    {
        $payload = $request->validate([
            'cv' => 'required|array',
            'correlation_id' => 'nullable|string',
            'dispatch_event' => 'nullable|boolean',
        ]);

        $result = $this->gateway->analyzeCv(
            $payload['cv'],
            $payload['correlation_id'] ?? null,
            $payload['dispatch_event'] ?? true,
        );

        return response()->json($result);
    }
}
