<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;

class PostController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'body' => 'required|string',
            'image' => 'required|image|max:2048', // 2MB Max
            'education' => 'required|string',
            'skills' => 'required|array', // Skills is an array of strings
            'skills.*' => 'string',
            'additional_links' => 'required|string',
            'resume' => 'required|file|max:2048',

        ]);

        // Assuming you have a Post model with title, description and image fields
        $post = new Post();
        $post->user_id = auth()->id();
        $post->title = $request->title;
        $post->body = $request->body;
        $post->education = $request->education;
        $post->skills = is_array($request->skills) ? implode(', ', $request->skills) : $request->skills;
        $post->resume = $request->resume;
        $post->additional_links = $request->additional_links;

        $user = auth()->user();
        if ($user)
            $post->name = $user->name;
        
        // Handle file upload
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('public/posts');
            $post->image = basename($imagePath); // Store image name only
        }

        if ($request->hasFile('resume')) {
            $resumePath = $request->file('resume')->store('public/resumes');
            $post->resume = basename($resumePath); // Store the resume file name only
        }

        $request->validate([
         
            'skills' => 'required|array',
            'skills.*' => 'string|max:255', 
   
        ]);
        
        $post->published_at = now(); // or $request->published_at if your form provides it
        $post->featured = 0;
        $post->save();

        return redirect()->route('HomePage')->with('status', 'Post created successfully!');
    }

    public function show($id)
    {
        $post = Post::with('comments.user')->findOrFail($id);
        return view('posts', compact('post'));
        
    }

    public function index()
    {
        $posts = Post::all();  // Fetches all posts
        return view('posts', compact('posts'));

    }

}
