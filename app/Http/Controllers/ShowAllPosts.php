<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;

class ShowAllPosts extends Controller
{
    public function index()
    {
        return view('all_posts', [
            'allPosts' => Post::all()
        ]);
    }
}
