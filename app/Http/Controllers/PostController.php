<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\User;
use Illuminate\Support\Facades\Auth;


class PostController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'body' => 'required|string',
            'image' => 'required|image|max:2048', // 2MB Max
            'education' => 'nullable|string', // Optional for employers
            'skills' => 'required|array', // Required for both
            'skills.*' => 'string',
            'salary' => 'nullable|numeric', // Employer only
            'additional_links' => 'nullable|string',
            'resume' => 'nullable|file|max:2048', // Simple user only
        ]);

        $post = new Post();

        if (auth('employer')->check()) {
            // Employer creating a job offer
            $post->employer_id = auth('employer')->id();
            $post->name = auth('employer')->user()->name;
            $post->post_type = 'job_offer';
            $post->skills = implode(', ', $validated['skills']); // Required for employer
            $post->salary = $validated['salary'] ?? null;
        } elseif (auth()->check()) {
            // User creating a resume post
            $post->user_id = auth()->id();
            $post->name = auth()->user()->name;
            $post->post_type = 'resume';
            $post->education = $validated['education'] ?? null;
            $post->skills = implode(', ', $validated['skills']); // Simple users now can add skills
        }

        // Common fields
        $post->title = $validated['title'];
        $post->body = $validated['body'];
        $post->additional_links = $validated['additional_links'] ?? null;

        // Handle image upload
        if ($request->hasFile('image')) {
            $post->image = $request->file('image')->store('public/posts');
            $post->image = basename($post->image);
        }

        // Handle resume upload for users
        if ($request->hasFile('resume')) {
            $post->resume = $request->file('resume')->store('public/resumes');
            $post->resume = basename($post->resume);
        }

        $post->published_at = now();
        $post->featured = 0;
        $post->save();

        // Redirect based on user type
        return auth('employer')->check()
            ? redirect()->route('employer.dashboard')->with('status', 'Job offer created successfully!')
            : redirect()->route('HomePage')->with('status', 'Resume post created successfully!');
    }

    public function show($id)
    {
        // Fetch the post with related comments
        $post = Post::with('comments.user')->findOrFail($id);

        // Check the post type and return the appropriate view
        if ($post->post_type === 'job_offer') {
            return view('employer.posts', compact('post')); // Employer Blade for job offers
        } elseif ($post->post_type === 'resume') {
            return view('posts', compact('post')); // Default Blade for resumes
        }

        // Handle unexpected post types
        abort(404, 'Post type not recognized.');
    }

    public function index()
    {
        $posts = Post::all(); 
        return view('posts', compact('posts'));
    }


    public function viewApplicants($postId)
    {
        $post = Post::with('applicants')->findOrFail($postId);

        // Ensure only the post's employer can view the applicants
        if (auth('employer')->id() !== $post->employer_id) {
            abort(403, 'Unauthorized');
        }

        return view('employer.applicants', ['post' => $post]);
    }

    public function destroy($id)
    {
        $post = Post::findOrFail($id);

        // If an employer is logged in, check employer ownership
        if (auth('employer')->check()) {
            if (auth('employer')->id() !== $post->employer_id) {
                abort(403, 'You do not own this post.');
            }
            // Delete the post
            $post->delete();

            // Redirect employer to /employer/MyPosts
            return redirect()
                ->route('employer.portfolios')
                ->with('status', 'Post deleted successfully!');
        }

        // Otherwise, check if a normal user is logged in
        elseif (auth()->check()) {
            if (auth()->id() !== $post->user_id) {
                abort(403, 'You do not own this post.');
            }
            // Delete the post
            $post->delete();

            // Redirect user to /MyPosts
            return redirect()
                ->route('portfolios.index')
                ->with('status', 'Post deleted successfully!');
        }

        // No authenticated user at all?
        abort(403, 'Unauthorized action.');
    }




    public function apply(Request $request, $postId)
    {
        $request->validate([
            'cv_file' => 'required|file|mimes:pdf,doc,docx|max:2048',
        ]);

        $post = Post::findOrFail($postId);
        $user = Auth::user();

        // Check if the user has already applied
        if ($post->applicants()->where('user_id', $user->id)->exists()) {
            return back()->with('error', 'You have already applied for this job.');
        }

        // Store the CV
        $cvPath = $request->file('cv_file')->store('public/cvs');

        // Attach user to the post with CV path
        $post->applicants()->attach($user->id, ['cv_path' => basename($cvPath)]);

        return back()->with('success', 'You have successfully applied for this job.');
    }


}
