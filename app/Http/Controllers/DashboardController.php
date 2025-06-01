<?php

namespace App\Http\Controllers;
use App\Models\Post;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        return view('dashboard', [
            'featuredPosts' => Post::where('post_type', 'job_offer')
                ->where('featured', true)
                ->latest('published_at')
                ->take(3)
                ->get(),

            'latestPosts' => Post::where('post_type', 'job_offer')
                ->latest('published_at')
                ->take(9)
                ->get(),
        ]);
    }
}
