<?php

use App\Http\Controllers\Api\V1\AtsGatewayController;
use App\Http\Controllers\Api\V1\CvGatewayController;
use App\Http\Controllers\Api\V1\SupportBotController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    Route::post('cv/generate', [CvGatewayController::class, 'generate']);
    Route::post('cv/apply-fixes', [CvGatewayController::class, 'applyFixes']);

    Route::post('ats/analyze', [AtsGatewayController::class, 'analyze']);

    Route::post('support/sessions', [SupportBotController::class, 'start']);
    Route::post('support/sessions/{sessionId}/messages', [SupportBotController::class, 'progress']);
});

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
