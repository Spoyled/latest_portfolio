<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Services\Gateway\TalentPlatformGateway;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SupportBotController extends Controller
{
    public function __construct(
        private readonly TalentPlatformGateway $gateway,
    ) {
    }

    public function start(Request $request): JsonResponse
    {
        $data = $request->validate([
            'persona' => 'nullable|string|in:candidate,employer,guest',
        ]);

        $snapshot = $this->gateway->startSupportSession($data['persona'] ?? null);

        return response()->json($snapshot, 201);
    }

    public function progress(string $sessionId, Request $request): JsonResponse
    {
        $data = $request->validate([
            'option_id' => 'required|string',
        ]);

        $snapshot = $this->gateway->progressSupportSession($sessionId, $data['option_id']);

        return response()->json($snapshot);
    }
}
