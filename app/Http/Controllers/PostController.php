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
            // For the body, limit to, say, 2000 characters.
            'body' => 'required|string|max:2000',

            // Only accept images and certain formats (no mp4)
            // Adjust 'mimes' if you need to allow more or fewer formats.
            'image' => 'required|file|mimes:jpg,jpeg,png,gif,webp|max:2048',

            // These fields are used conditionally but we can still validate them.
            'education' => 'nullable|string|max:255', 
            'skills' => 'required|array', 
            'skills.*' => 'string|max:100',
            'location' => 'required_if:isEmployer,true|max:255',
            'position' => 'required_if:isEmployer,true|max:255',
            'salary' => 'nullable|numeric',

            // We'll force the additional_links to be a valid URL
            // If you’d prefer not to enforce a URL format, you can use 'string' only.
            'additional_links' => 'nullable|url',

            // Resume only for "simple user" type, restricting to PDF, DOC, DOCX:
            'resume' => 'nullable|file|mimes:pdf,doc,docx|max:2048',
        ]);

        $post = new Post();

        if (auth('employer')->check()) {
            // Employer creating a job offer
            $post->employer_id = auth('employer')->id();
            $post->name = auth('employer')->user()->name;
            $post->post_type = 'job_offer';
            $post->skills = implode(', ', $validated['skills']); // Required for employer
            $post->location = $validated['location'] ?? null; // Store location
            $post->position = $validated['position'] ?? null; // Store location
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
        $post = Post::with('comments.user')->findOrFail($id);

        if ($post->post_type === 'job_offer') {
            if (!auth('employer')->check() && $post->closed_at !== null) {
                abort(403, 'This job offer is no longer available.');
            }

            return view('employer.posts', compact('post'));
        }

        if ($post->post_type === 'resume') {
            return view('posts', compact('post'));
        }

        abort(404, 'Post type not recognized.');
    }


    public function index()
    {
        $query = Post::query();

        // If the user is NOT an employer, hide closed posts
        if (!auth('employer')->check()) {
            $query->whereNull('closed_at');
        }

        $posts = $query->latest()->get();

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

    public function markAsRecruited($postId, $userId)
    {
        $post = Post::findOrFail($postId);

        if (auth('employer')->id() !== $post->employer_id) {
            abort(403);
        }

        $post->applicants()->updateExistingPivot($userId, [
            'recruited' => true,
        ]);

        return back()->with('success', 'Applicant marked as recruited.');
    }

    public function declineApplicant($postId, $userId)
    {
        $post = Post::findOrFail($postId);

        if (auth('employer')->id() !== $post->employer_id) {
            abort(403);
        }

        $post->applicants()->updateExistingPivot($userId, [
            'declined' => true,
        ]);

        return back()->with('success', 'Applicant marked as declined.');
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
        $post = Post::findOrFail($postId);
        $user = Auth::user();

        if ($post->applicants()->where('user_id', $user->id)->exists()) {
            return back()->with('error', 'You have already applied for this job.');
        }

        $request->validate([
            'cv_file' => 'nullable|file|mimes:pdf,doc,docx|max:2048',
        ]);

        if (!$request->hasFile('cv_file') && !$user->cv_path) {
            return back()
                ->withErrors(['cv_file' => 'You must upload a CV before applying.'])
                ->withInput();
        }

        if ($request->hasFile('cv_file')) {
            $cvPath = $request->file('cv_file')->store('public/cvs');
            $finalCvPath = basename($cvPath);
        } else {
            $finalCvPath = basename($user->cv_path);
        }

        $post->applicants()->attach($user->id, ['cv_path' => $finalCvPath]);

        return back()->with('success', 'You have successfully applied for this job.');
    }


    public function edit(Post $post)
    {   
        if (!auth('employer')->check() || auth('employer')->id() !== $post->employer_id) {
            abort(403);
        }

        return view('employer.edit-post', compact('post'));
    }

    public function update(Request $request, Post $post)
    {
            if (!auth('employer')->check() || auth('employer')->id() !== $post->employer_id) {
                abort(403);
            }

            $validated = $request->validate([
                'title' => ['required', 'string', 'max:255'],
                'body' => ['required', 'string', 'max:2000'],
                'image' => ['nullable', 'file', 'mimes:jpg,jpeg,png,gif,webp', 'max:2048'],
                'location' => ['required', 'string', 'max:255', 'regex:/^[\pL\s\-]+$/u'],
                'position' => ['required', 'string', 'max:255', 'regex:/^[\pL\s\-]+$/u'],
                'salary' => ['nullable', 'numeric'],
                'skills' => ['required', 'string', 'max:255', 'regex:/^[\pL\s,]+$/u'],
                'additional_links' => ['nullable', 'url'],
            ]);
            

            $post->update([
                'title' => $validated['title'],
                'body' => $validated['body'],
                'location' => $validated['location'],
                'position' => $validated['position'],
                'salary' => $validated['salary'],
                'skills' => $validated['skills'], 
                'additional_links' => $validated['additional_links'] ?? null,
            ]);

            if ($request->hasFile('image')) {
                try {
                    $post->image = basename($request->file('image')->store('public/posts'));
                    $post->save();
                } catch (\Exception $e) {
                    return back()->withErrors(['image' => 'The image failed to upload.'])->withInput();
                }
            }
            

            return redirect()->route('employer.posts.show', $post->id)->with('status', 'Post updated successfully!');

    }

    public function editUser(Post $post)
    {
        if (!auth()->check() || auth()->id() !== $post->user_id) {
            abort(403);
        }

        return view('edit-post', compact('post'));
    }

    public function updateUser(Request $request, Post $post)
    {
        if (!auth()->check() || auth()->id() !== $post->user_id) {
            abort(403);
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'body' => 'required|string|max:2000',
            'education' => 'nullable|string|max:255|regex:/^[\pL\s,]+$/u',
            'skills' => 'required|string|max:255|regex:/^[\pL\s,]+$/u',
            'additional_links' => 'nullable|url',
            'image' => 'nullable|file|mimes:jpg,jpeg,png,gif,webp|max:2048',
            'resume' => 'nullable|file|mimes:pdf,doc,docx|max:2048',
        ]);

        $post->update([
            'title' => $validated['title'],
            'body' => $validated['body'],
            'education' => $validated['education'],
            'skills' => $validated['skills'],
            'additional_links' => $validated['additional_links'] ?? null,
        ]);

        if ($request->hasFile('image')) {
            $post->image = basename($request->file('image')->store('public/posts'));
            $post->save();
        }

        if ($request->hasFile('resume')) {
            $post->resume = basename($request->file('resume')->store('public/resumes'));
            $post->save();
        }

        return redirect()->route('posts.show', $post->id)->with('status', 'Resume updated successfully!');
    }





    public function close(Post $post)
    {
        if (auth('employer')->id() !== $post->employer_id) {

            if (request()->expectsJson()) {
                return response()->json(['error' => 'Unauthorized'], 403);
            }
            abort(403);
        }

        $post->closed_at = now();
        $post->save();

        if (request()->expectsJson()) {
            return response()->json(['message' => 'Post closed successfully.']);
        }

        return back()->with('status', 'Post successfully closed. It will be deleted in 2 weeks.');
    }

}
