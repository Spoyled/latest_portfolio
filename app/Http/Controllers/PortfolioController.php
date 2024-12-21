<?php

namespace App\Http\Controllers;
use App\Models\Post;
use Illuminate\Http\Request;

class PortfolioController extends Controller
{
    public function index()
    {
        if (auth()->guard('employer')->check()) {
            // For employers, fetch posts where the employer is the creator
            $myPosts = Post::where('employer_id', auth()->guard('employer')->id())->latest('published_at')->take(32)->get();
        } else {
            // For regular users, fetch posts by user_id
            $myPosts = Post::where('user_id', auth()->id())->latest('published_at')->take(32)->get();
        }

        return view('portfolios', compact('myPosts'));
    }

}