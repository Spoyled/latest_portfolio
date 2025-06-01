<?php

namespace App\Http\Controllers;
use App\Models\Post;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __invoke(Request $request)
    {
        $baseQuery = Post::where('post_type', 'job_offer');

        if (!auth('employer')->check()) {
            $baseQuery->whereNull('closed_at');
        }

        return view('dashboard', [
            'featuredPosts' => (clone $baseQuery)
                ->where('featured', true)
                ->latest('published_at')
                ->take(3)
                ->get(),

            'latestPosts' => (clone $baseQuery)
                ->latest('published_at')
                ->take(9)
                ->get(),
        ]);
    }
}
