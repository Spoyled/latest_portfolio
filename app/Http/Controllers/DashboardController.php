<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\PostsApi;
use Carbon\Carbon;

class DashboardController extends Controller
{
    private PostsApi $api;

    public function __construct(PostsApi $api)
    {
        $this->api = $api;
    }

    public function __invoke(Request $request)
    {
        $all = $this->api->list(); // array<array>
        $isEmployer = Auth::guard('employer')->check();

        // show only active job offers; hide closed for non-employers
        $visible = array_values(array_filter($all, function ($p) use ($isEmployer) {
            if (($p['post_type'] ?? null) !== 'job_offer') return false;
            if (!$isEmployer && !empty($p['closed_at'])) return false;
            return (int)($p['is_active'] ?? 1) === 1;
        }));

        // newest first
        usort($visible, fn ($a, $b) =>
            strcmp(($b['published_at'] ?? $b['created_at'] ?? ''),
                   ($a['published_at'] ?? $a['created_at'] ?? ''))
        );

        // helper: to object + Carbon dates (nice for Blade)
        $toObj = function (array $p) {
            $p['published_at'] = isset($p['published_at'])
                ? Carbon::parse($p['published_at'])
                : (isset($p['created_at']) ? Carbon::parse($p['created_at']) : null);
            return (object) $p;
        };

        // latest (9)
        $latest = array_map($toObj, array_slice($visible, 0, 9));

        // featured (3)
        $featuredRaw = array_values(array_filter($visible, fn ($p) => (int)($p['featured'] ?? 0) === 1));
        usort($featuredRaw, fn ($a, $b) =>
            strcmp(($b['published_at'] ?? $b['created_at'] ?? ''),
                   ($a['published_at'] ?? $a['created_at'] ?? ''))
        );
        $featured = array_map($toObj, array_slice($featuredRaw, 0, 3));

        // return Collections so ->isEmpty() in Blade works
        return view('dashboard', [
            'featuredPosts' => collect($featured),
            'latestPosts'   => collect($latest),
        ]);
    }
}
