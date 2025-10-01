<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class PostsApi
{
    private string $base;

    public function __construct()
    {
        $this->base = rtrim(config('services.posts.base', env('POSTS_API_BASE', 'http://gateway/api/posts')), '/');
    }

    public function list(array $query = []): array
    {
        $url = "{$this->base}/posts";
        if (!empty($query)) {
            $url .= '?' . http_build_query($query);
        }

        $r = Http::retry(2, 200)->timeout(8)->get($url);
        return $r->successful() ? ($r->json() ?? []) : [];
    }

    public function get($id): array
    {
        $r = Http::retry(2, 200)->timeout(8)->get("{$this->base}/posts/{$id}");
        return $r->successful() ? ($r->json() ?? []) : [];
    }

    public function create(array $payload): array
    {
        $r = Http::retry(2, 200)->timeout(8)->post("{$this->base}/posts", $payload);
        if (!$r->successful()) abort(422, $r->json('message', 'Validation error'));
        return $r->json();
    }

    public function delete($id): bool
    {
        $r = Http::retry(2, 200)->timeout(8)->delete("{$this->base}/posts/{$id}");
        return $r->successful();
    }

    public function update($id, array $payload): array
    {
        // Try legacy first: .../api/posts/posts/{id}
        $r1 = Http::retry(2, 200)->timeout(8)
            ->asJson()->acceptJson()
            ->put("{$this->base}/posts/{$id}", $payload);

        if ($r1->noContent()) return [];
        if ($r1->successful()) {
            $json = $r1->json();
            return is_array($json) ? $json : [];
        }

        // Only fall back if the route truly doesn't exist
        if ($r1->status() !== 404) {
            abort($r1->status() >= 500 ? 500 : 422, $r1->json('message', 'Validation/Server error'));
        }

        // Fallback: .../api/posts/{id}
        $r2 = Http::retry(2, 200)->timeout(8)
            ->asJson()->acceptJson()
            ->put("{$this->base}/{$id}", $payload);

        if ($r2->noContent()) return [];
        if (!$r2->successful()) {
            abort($r2->status() >= 500 ? 500 : 422, $r2->json('message', 'Validation/Server error'));
        }

        $json = $r2->json();
        return is_array($json) ? $json : [];
    }

    public function patch($id, array $payload): array
    {
        // Try legacy first: .../api/posts/posts/{id}
        $r1 = Http::retry(2, 200)->timeout(8)
            ->asJson()->acceptJson()
            ->patch("{$this->base}/posts/{$id}", $payload);

        if ($r1->noContent()) return [];
        if ($r1->successful()) {
            $json = $r1->json();
            return is_array($json) ? $json : [];
        }

        // Only fall back if the route truly doesn't exist
        if ($r1->status() !== 404) {
            abort($r1->status() >= 500 ? 500 : 422, $r1->json('message', 'Validation/Server error'));
        }

        // Fallback: .../api/posts/{id}
        $r2 = Http::retry(2, 200)->timeout(8)
            ->asJson()->acceptJson()
            ->patch("{$this->base}/{$id}", $payload);

        if ($r2->noContent()) return [];
        if (!$r2->successful()) {
            abort($r2->status() >= 500 ? 500 : 422, $r2->json('message', 'Validation/Server error'));
        }

        $json = $r2->json();
        return is_array($json) ? $json : [];
    }



}
