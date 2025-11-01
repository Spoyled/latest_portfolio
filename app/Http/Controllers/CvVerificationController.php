<?php

namespace App\Http\Controllers;

use App\Models\CvVersion;

class CvVerificationController extends Controller
{
    public function __invoke(string $hash)
    {
        $cvVersion = CvVersion::with('user')->where('sha256_hash', $hash)->first();

        if (!$cvVersion) {
            return view('cv.verification_failed', [
                'attemptedHash' => $hash,
            ]);
        }

        $payload = $cvVersion->data ?? [];

        return view('cv.verification_success', [
            'cvVersion'        => $cvVersion,
            'verificationHash' => $hash,
            'meta'             => $payload['meta'] ?? [],
            'analysis'         => $payload['analysis_snapshot'] ?? [],
        ]);
    }
}
