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
    return view('employer.home', [ // Ensure this matches the view path
        'featuredPosts' => Post::where('featured', '1')->latest('published_at')->take(3)->get(),
        'latestPosts' => Post::latest('published_at')->take(9)->get(),
    ]);
}

}
