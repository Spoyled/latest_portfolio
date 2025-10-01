<?php

namespace App\Http\Controllers;

use App\Services\PostsApi;

class EmployerDashboardController extends Controller
{
    public function __construct(private PostsApi $api) {}

    /**
     * Show the employer's dashboard.
     * Employers see candidate posts (post_type = 'resume').
     */
    public function index()
    {
        $rows = $this->api->list(); // array<array>

        // Only active, not closed, resumes
        $rows = array_values(array_filter($rows, function ($p) {
            return (int)($p['is_active'] ?? 1) === 1
                && empty($p['closed_at'])
                && (($p['post_type'] ?? '') === 'resume');
        }));

        // Sort newest first
        usort($rows, function ($a, $b) {
            $aTime = strtotime($a['published_at'] ?? $a['created_at'] ?? '1970-01-01');
            $bTime = strtotime($b['published_at'] ?? $b['created_at'] ?? '1970-01-01');
            return $bTime <=> $aTime;
        });

        // Helper: robust "is featured?"
        $isFeatured = static function (array $p): bool {
            $v = $p['featured'] ?? 0;
            if (is_bool($v)) return $v;
            if (is_numeric($v)) return ((int)$v) === 1;
            return in_array(strtolower((string)$v), ['1','true','yes','on'], true);
        };

        // Strict featured (no fallback)
        $featured = array_values(array_filter($rows, $isFeatured));

        // Latest = newest excluding featured
        $latest = array_values(array_filter($rows, fn($p) => !$isFeatured($p)));
        $latest = array_slice($latest, 0, 9);

        $featuredPosts = collect(array_map(fn($p) => (object)$p, $featured));
        $latestPosts   = collect(array_map(fn($p) => (object)$p, $latest));

        return view('employer.home', compact('featuredPosts', 'latestPosts'));
    }

}
