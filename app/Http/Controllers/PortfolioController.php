<?php

namespace App\Http\Controllers;
use App\Models\Post;
use Illuminate\Http\Request;

class PortfolioController extends Controller
{
    public function index()
    {
        return view('portfolios', [
            'myPosts' => Post::where('user_id', auth()->id())->latest('published_at')->take(32)->get()
        ]);
    }
}