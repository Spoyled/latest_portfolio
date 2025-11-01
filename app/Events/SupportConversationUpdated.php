<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class SupportConversationUpdated
{
    use Dispatchable;
    use InteractsWithSockets;
    use SerializesModels;

    /**
     * @param  string  $sessionId  Identifier of the support conversation session
     * @param  array   $payload    Current state snapshot (messages, options, metadata)
     */
    public function __construct(
        public readonly string $sessionId,
        public readonly array $payload,
    ) {
    }
}

