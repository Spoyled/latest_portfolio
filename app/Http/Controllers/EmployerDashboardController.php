<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class EmployerDashboardController extends Controller
{
    /**
     * Show the employer's dashboard.
     */
    public function index()
    {
        $featuredPosts = Post::where('post_type', 'resume')
            ->where('featured', true)
            ->latest('published_at')
            ->take(3)
            ->get();

        $latestPosts = Post::where('post_type', 'resume')
            ->latest('published_at')
            ->take(9)
            ->get();

        return view('employer.home', compact('featuredPosts', 'latestPosts'));
    }


}
