<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Services\PostsApi;
use Carbon\Carbon;

class PortfolioController extends Controller
{
    private PostsApi $api;

    public function __construct(PostsApi $api)
    {
        $this->api = $api;
    }

    public function index()
    {
        $all = $this->api->list(); // array<array>

        if (Auth::guard('employer')->check()) {
            $ownerId = Auth::guard('employer')->id();
            // employer’s own job offers
            $mine = array_values(array_filter($all, fn ($p) =>
                ($p['employer_id'] ?? null) == $ownerId &&
                ($p['post_type'] ?? '') === 'job_offer'
            ));
        } else {
            $ownerId = Auth::id();
            // user’s own resumes
            $mine = array_values(array_filter($all, fn ($p) =>
                ($p['user_id'] ?? null) == $ownerId &&
                ($p['post_type'] ?? '') === 'resume'
            ));
        }

        // normalize for blade (objects + parsed dates), newest first, max 32
        $mine = array_map(function ($p) {
            if (!empty($p['published_at'])) {
                try { $p['published_at'] = Carbon::parse($p['published_at']); } catch (\Throwable) {}
            }
            return (object) $p;
        }, $mine);

        usort($mine, fn ($a, $b) =>
            strcmp((string)($b->published_at ?? ''), (string)($a->published_at ?? ''))
        );

        $myPosts = collect(array_slice($mine, 0, 32));

        return view('portfolios', compact('myPosts'));
    }
}
