<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;

class ShowAllPosts extends Controller
{
    public function index()
    {
        // Check if the authenticated user is an employer
        if (auth('employer')->check()) {
            // Show all resume posts for employers
            $allPosts = Post::where('post_type', 'resume')->latest('published_at')->paginate(10);
        } elseif (auth()->check()) {
            // Show all job offer posts for regular users
            $allPosts = Post::where('post_type', 'job_offer')->latest('published_at')->paginate(10);
        } else {
            // For guests, show all featured posts or empty (optional)
            $allPosts = Post::where('featured', true)->latest('published_at')->paginate(10);
        }

        // Return the view with filtered posts
        return view('all_posts', compact('allPosts'));
    }
}
