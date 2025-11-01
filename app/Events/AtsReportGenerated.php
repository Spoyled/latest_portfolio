<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class AtsReportGenerated
{
    use Dispatchable;
    use InteractsWithSockets;
    use SerializesModels;

    /**
     * @param  string  $correlationId  Identifier tying the report back to the originating request
     * @param  array   $report         ATS report payload including score, warnings, highlights, etc.
     */
    public function __construct(
        public readonly string $correlationId,
        public readonly array $report,
    ) {
    }
}

