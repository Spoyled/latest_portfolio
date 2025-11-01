<?php

namespace App\Services\Support;

use App\Events\SupportConversationUpdated;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Str;

class SupportBotService
{
    private const CACHE_PREFIX = 'support_bot_session:';
    private const SESSION_TTL = 900; // 15 minutes

    public function startSession(?string $persona = null): array
    {
        $sessionId = (string) Str::uuid();
        $state = $persona === 'employer' ? 'employer_welcome' : 'intro';

        $snapshot = $this->buildSnapshot($state, []);
        $snapshot['session_id'] = $sessionId;
        $snapshot['state'] = $state;
        $snapshot['history'] = [];

        $this->storeSession($sessionId, $snapshot);
        SupportConversationUpdated::dispatch($sessionId, $snapshot);

        return $snapshot;
    }

    public function progress(string $sessionId, string $optionId): array
    {
        $session = $this->getSession($sessionId);
        $history = Arr::get($session, 'history', []);
        $currentState = Arr::get($session, 'state', 'intro');

        $node = $this->getNode($currentState);
        $targetState = collect($node['options'] ?? [])
            ->firstWhere('id', $optionId)['id'] ?? null;

        if (! $targetState) {
            return $this->buildSnapshot($currentState, $history, $sessionId, $optionId, true);
        }

        $history[] = [
            'state' => $currentState,
            'selected_option' => $optionId,
        ];

        $snapshot = $this->buildSnapshot($targetState, $history, $sessionId, $optionId);
        $this->storeSession($sessionId, $snapshot);

        SupportConversationUpdated::dispatch($sessionId, $snapshot);

        return $snapshot;
    }

    public function getSession(string $sessionId): array
    {
        return Cache::get(self::CACHE_PREFIX.$sessionId, [
            'session_id' => $sessionId,
            'state' => 'intro',
            'history' => [],
        ]);
    }

    private function storeSession(string $sessionId, array $snapshot): void
    {
        Cache::put(self::CACHE_PREFIX.$sessionId, $snapshot, self::SESSION_TTL);
    }

    private function buildSnapshot(
        string $state,
        array $history,
        ?string $sessionId = null,
        ?string $selectedOption = null,
        bool $invalidOption = false,
    ): array {
        $node = $this->getNode($state);

        return [
            'session_id' => $sessionId ?? (string) Str::uuid(),
            'state' => $state,
            'message' => $node['message'] ?? 'I\'m not sure how to help with that yet.',
            'options' => $node['options'] ?? [],
            'history' => $history,
            'selected_option' => $selectedOption,
            'invalid_option' => $invalidOption,
        ];
    }

    private function getNode(string $state): array
    {
        $nodes = Config::get('support-bot.nodes', []);

        return $nodes[$state] ?? [
            'message' => 'Let\'s start over.',
            'options' => [
                ['id' => 'intro', 'label' => 'Main menu'],
            ],
        ];
    }
}
