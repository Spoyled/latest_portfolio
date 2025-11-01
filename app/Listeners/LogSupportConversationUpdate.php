<?php

namespace App\Listeners;

use App\Events\SupportConversationUpdated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;

class LogSupportConversationUpdate implements ShouldQueue
{
    public function handle(SupportConversationUpdated $event): void
    {
        Log::info('Support conversation progressed', [
            'session_id' => $event->sessionId,
            'state' => $event->payload['state'] ?? null,
            'selected_option' => $event->payload['selected_option'] ?? null,
        ]);
    }
}

