<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CvGenerated
{
    use Dispatchable;
    use InteractsWithSockets;
    use SerializesModels;

    /**
     * @param  string  $correlationId  Correlation identifier for cross-service tracking
     * @param  array   $cvPayload      Normalised CV data used during generation
     * @param  string  $documentPath   Storage-relative path to the generated document
     * @param  string  $analysisMode   Requested ATS analysis mode: none|sync|async
     */
    public function __construct(
        public readonly string $correlationId,
        public readonly array $cvPayload,
        public readonly string $documentPath,
        public readonly string $analysisMode = 'async',
    ) {
    }
}

