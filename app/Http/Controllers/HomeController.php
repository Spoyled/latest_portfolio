<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\PostsApi;
use Carbon\Carbon;

class HomeController extends Controller
{
    private PostsApi $api;

    public function __construct(PostsApi $api)
    {
        $this->api = $api;
    }

    public function __invoke(Request $request)
    {
        // same redirects you had in the route closure
        if (Auth::guard('employer')->check()) {
            return redirect()->route('employer.dashboard');
        }
        if (Auth::check()) {
            return redirect()->route('HomePage');
        }

        // fetch from microservice
        $all = $this->api->list(); // array<array>

        // only active, not closed employer job offers
        $visible = array_values(array_filter($all, function ($p) {
            $isActive = (int)($p['is_active'] ?? 1) === 1;
            $isOpen = empty($p['closed_at']);
            $isJobOffer = ($p['post_type'] ?? '') === 'job_offer';
            return $isActive && $isOpen && $isJobOffer;
        }));

        // map to objects + normalize dates for blade
        $toObj = function (array $p) {
            $p['published_at'] = isset($p['published_at'])
                ? Carbon::parse($p['published_at'])
                : (isset($p['created_at']) ? Carbon::parse($p['created_at']) : null);
            return (object) $p;
        };

        // featured (limit 3)
        $featured = array_values(array_filter($visible, fn ($p) => (int)($p['featured'] ?? 0) === 1));
        usort($featured, fn ($a, $b) =>
            strcmp(($b['published_at'] ?? $b['created_at'] ?? ''), ($a['published_at'] ?? $a['created_at'] ?? ''))
        );
        $featuredPosts = array_map($toObj, array_slice($featured, 0, 3));

        // latest (limit 9)
        usort($visible, fn ($a, $b) =>
            strcmp(($b['published_at'] ?? $b['created_at'] ?? ''), ($a['published_at'] ?? $a['created_at'] ?? ''))
        );
        $latestPosts = array_map($toObj, array_slice($visible, 0, 9));

        // IMPORTANT: use the same view name your route closure used ('home')
        return view('home', [
            'featuredPosts' => $featuredPosts,
            'latestPosts'   => $latestPosts,
            'posts'         => $latestPosts,   // <- alias for legacy Blade
        ]);

    }
}
