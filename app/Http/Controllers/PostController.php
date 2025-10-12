<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Services\PostsApi;
use App\Models\Comment;
use Illuminate\Support\Facades\Validator; 
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Support\ResumeBlueprint;


class PostController extends Controller
{
    private PostsApi $api;

    public function __construct(PostsApi $api)
    {
        $this->api = $api;
    }

    public function store(Request $request)
    {
        $isEmployer = auth('employer')->check();

        if ($isEmployer) {
            $validated = $request->validate([
                'title'          => ['required','string','max:255'],
                'body'           => ['required','string','max:2000'],
                'skills'         => ['required','array','min:1'],
                'skills.*'       => ['string','max:100'],
                'location'       => ['required','string','max:255','regex:/^[\pL\s\-]+$/u'],
                'position'       => ['required','string','max:255','regex:/^[\pL\s\-]+$/u'],
                'salary'         => ['nullable','numeric'],
                'additional_links' => ['nullable','url'],
                'image'          => ['required','file','mimes:jpg,jpeg,png,gif,webp','max:2048'],
            ]);

            $body = $validated['body'];
            $educationValue = null;
        } else {
            $validated = $request->validate([
                'title'          => ['required','string','max:255'],
                'summary'        => ['required','string','max:600'],
                'highlights'     => ['nullable','string','max:600'],
                'ideal_role'     => ['required','string','max:400'],
                'collaboration'  => ['nullable','string','max:400'],
                'availability'   => ['nullable','string','max:300'],
                'education'      => ['nullable','string','max:255'],
                'skills'         => ['required','array','min:1'],
                'skills.*'       => ['string','max:100'],
                'additional_links' => ['nullable','url'],
                'image'          => ['required','file','mimes:jpg,jpeg,png,gif,webp','max:2048'],
                'resume'         => ['nullable','file','mimes:pdf,doc,docx','max:2048'],
            ]);

            $body = ResumeBlueprint::build([
                'summary'       => $validated['summary'],
                'highlights'    => $validated['highlights'] ?? null,
                'ideal_role'    => $validated['ideal_role'],
                'collaboration' => $validated['collaboration'] ?? null,
                'availability'  => $validated['availability'] ?? null,
            ]);

            $educationValue = $validated['education'] ?? null;
        }

        $skillsString = implode(', ', $validated['skills']);

        $imageName = null;
        $resumeName = null;
        if ($request->hasFile('image')) {
            $imageName = basename($request->file('image')->store('public/posts'));
        }
        if ($request->hasFile('resume')) {
            $resumeName = basename($request->file('resume')->store('public/resumes'));
        }

        $payload = [
            'title' => $validated['title'],
            'body'  => $body,
            'additional_links' => $validated['additional_links'] ?? null,
            'image' => $imageName,
            'resume'=> $resumeName,
            'published_at' => now()->toDateTimeString(),
            'featured' => 0,
            'is_active' => 1,
        ];

        if ($isEmployer) {
            $payload += [
                'employer_id' => auth('employer')->id(),
                'name'        => auth('employer')->user()->name,
                'post_type'   => 'job_offer',
                'skills'      => $skillsString,
                'location'    => $validated['location'] ?? null,
                'position'    => $validated['position'] ?? null,
                'salary'      => $validated['salary'] ?? null,
            ];
        } elseif (auth()->check()) {
            $payload += [
                'user_id'   => auth()->id(),
                'name'      => auth()->user()->name,
                'post_type' => 'resume',
                'education' => $educationValue,
                'skills'    => $skillsString,
            ];
        }

        $created = $this->api->create($payload);

        return $isEmployer
            ? redirect()->route('employer.dashboard')->with('status', 'Job offer created successfully!')
            : redirect()->route('HomePage')->with('status', 'Resume post created successfully!');
    }


    public function show(Request $request, $id)
    {
        $postArr = $this->api->get($id);
        if (!$postArr) abort(404);

        $post = (object) $postArr;
        $postModel = Post::find($id); // may be null, keep if you need it elsewhere

        // ✔ read applicants from the pivot table directly
        $applicantsCount = \DB::table('post_user_applications')->where('post_id', $id)->count();
        $hasApplied = auth()->check()
            ? \DB::table('post_user_applications')->where('post_id', $id)->where('user_id', auth()->id())->exists()
            : false;

        $openApply = $request->boolean('apply') || session('openApply', false);

        if (($post->post_type ?? null) === 'job_offer') {
            if (!auth('employer')->check() && !empty($post->closed_at)) {
                abort(403, 'This job offer is no longer available.');
            }
            return view('employer.posts', compact('post','postModel','openApply','applicantsCount','hasApplied'));
        }

        if (($post->post_type ?? null) === 'resume') {
            return view('posts', compact('post', 'postModel', 'openApply', 'applicantsCount', 'hasApplied'));
        }

        abort(404, 'Post type not recognized.');
    }

    public function index()
    {
        if (auth('employer')->check()) {
            // employer sees their own posts, including closed
            $posts = $this->api->list([
                'employer_id'    => auth('employer')->id(),
                'include_closed' => 1,
            ]);
        } else {
            // visitors/users see only open/active job offers
            $posts = $this->api->list();
            $posts = array_values(array_filter($posts, function ($p) {
                $isActive  = (int)($p['is_active'] ?? 1) === 1;
                $notClosed = empty($p['closed_at']);
                $isJob     = ($p['post_type'] ?? '') === 'job_offer';
                return $isActive && $notClosed && $isJob;
            }));
        }

        $posts = array_map(fn ($p) => (object) $p, $posts);
        return view('posts', compact('posts'));
    }





    public function viewApplicants($postId)
    {
        // 1) Post from API + ownership check
        $postArr = $this->api->get($postId);
        if (!$postArr || ($postArr['post_type'] ?? null) !== 'job_offer') abort(404);
        if ((int)($postArr['employer_id'] ?? 0) !== auth('employer')->id()) abort(403);

        // 2) Applicants from pivot + users
        $applicants = \DB::table('post_user_applications as pua')
            ->join('users', 'users.id', '=', 'pua.user_id')
            ->select(
                'users.id as user_id', 'users.name', 'users.email',
                'pua.cv_path', 'pua.recruited', 'pua.declined', 'pua.created_at'
            )
            ->where('pua.post_id', $postId)
            ->orderByDesc('pua.created_at')
            ->get();

        // 3) Pass as $post->applicants so your Blade keeps working
        $post = (object) $postArr;
        $post->applicants = $applicants;

        return view('employer.applicants', compact('post'));
    }


    public function markAsRecruited($postId, $userId)
    {
        $postArr = $this->api->get($postId);
        if (!$postArr || ($postArr['post_type'] ?? null) !== 'job_offer') abort(404);
        if ((int)($postArr['employer_id'] ?? 0) !== auth('employer')->id()) abort(403);

        DB::table('post_user_applications')
            ->where('post_id', $postId)
            ->where('user_id', $userId)
            ->update(['recruited' => true, 'updated_at' => now()]);

        return back()->with('success', 'Applicant marked as recruited.');
    }

    public function declineApplicant($postId, $userId)
    {
        $postArr = $this->api->get($postId);
        if (!$postArr || ($postArr['post_type'] ?? null) !== 'job_offer') abort(404);
        if ((int)($postArr['employer_id'] ?? 0) !== auth('employer')->id()) abort(403);

        DB::table('post_user_applications')
            ->where('post_id', $postId)
            ->where('user_id', $userId)
            ->update(['declined' => true, 'updated_at' => now()]);

        return back()->with('success', 'Applicant marked as declined.');
    }

    public function destroy($id)
    {
        $post = Post::findOrFail($id);

        if (auth('employer')->check()) {
            if (auth('employer')->id() !== $post->employer_id) {
                abort(403, 'You do not own this post.');
            }
            $this->api->delete($id);
            return redirect()->route('employer.portfolios')->with('status', 'Post deleted successfully!');
        } elseif (auth()->check()) {
            if (auth()->id() !== $post->user_id) {
                abort(403, 'You do not own this post.');
            }
            $this->api->delete($id);
            return redirect()->route('portfolios.index')->with('status', 'Post deleted successfully!');
        }

        abort(403, 'Unauthorized action.');
    }


    public function apply(Request $request, $postId)
    {
        $user = Auth::user();

        $postArr = $this->api->get($postId);
        if (!$postArr || (($postArr['post_type'] ?? null) !== 'job_offer')) abort(404);
        if (!empty($postArr['closed_at'])) {
            return redirect()->route('posts.show', $postId)
                ->withErrors(['job' => 'This job offer is closed.']);
        }

        $validator = Validator::make($request->all(), [
            'cv_file' => 'nullable|file|mimes:pdf,doc,docx|max:2048',
        ]);
        if ($validator->fails()) {
            return redirect()->to(route('posts.show', $postId) . '?apply=1')
                ->withErrors($validator)->withInput()->with('openApply', true);
        }

        if (!$request->hasFile('cv_file') && !$user->cv_path) {
            return redirect()->to(route('posts.show', $postId) . '?apply=1')
                ->withErrors(['cv_file' => 'You must upload a CV before applying.'])
                ->withInput()->with('openApply', true);
        }

        $finalCvPath = $request->hasFile('cv_file')
            ? basename($request->file('cv_file')->store('public/cvs'))
            : basename($user->cv_path);

        $exists = \DB::table('post_user_applications')
            ->where('post_id', $postId)->where('user_id', $user->id)->exists();

        if (!$exists) {
            \DB::table('post_user_applications')->updateOrInsert(
                ['post_id' => $postId, 'user_id' => $user->id],
                ['cv_path' => $finalCvPath, 'created_at' => now(), 'updated_at' => now()]
            );
        }

        // 👉 Success/Already applied: go back WITHOUT ?apply=1, so modal stays closed
        return redirect()->route('posts.show', $postId);
    }





    public function edit($postId)
    {
        // fetch from API and ensure ownership
        $postArr = $this->api->get($postId);
        if (!$postArr || ($postArr['post_type'] ?? null) !== 'job_offer') abort(404);

        if ((int)($postArr['employer_id'] ?? 0) !== auth('employer')->id()) {
            abort(403);
        }

        $post = (object) $postArr; // pass as object to the blade
        return view('employer.edit-post', compact('post'));
    }

    public function update(Request $request, $postId)
    {
        $postArr = $this->api->get($postId);
        if (!$postArr || ($postArr['post_type'] ?? null) !== 'job_offer') abort(404);
        if ((int)($postArr['employer_id'] ?? 0) !== auth('employer')->id()) abort(403);

        $validated = $request->validate([
            'title' => ['required','string','max:255'],
            'body'  => ['required','string','max:2000'],
            'image' => ['nullable','file','mimes:jpg,jpeg,png,gif,webp','max:2048'],
            'location' => ['required','string','max:255','regex:/^[\pL\s\-]+$/u'],
            'position' => ['required','string','max:255','regex:/^[\pL\s\-]+$/u'],
            'salary' => ['nullable','numeric'],
            'skills' => ['required','string','max:255','regex:/^[\pL\s,]+$/u'],
            'additional_links' => ['nullable','url'],
        ]);

        $imageName = $postArr['image'] ?? null;
        if ($request->hasFile('image')) {
            try {
                $imageName = basename($request->file('image')->store('public/posts'));
            } catch (\Throwable $e) {
                return back()->withErrors(['image' => 'The image failed to upload.'])->withInput();
            }
        }

        $payload = [
            'title' => $validated['title'],
            'body'  => $validated['body'],
            'location' => $validated['location'],
            'position' => $validated['position'],
            'salary' => $validated['salary'],
            'skills' => $validated['skills'],
            'additional_links' => $validated['additional_links'] ?? null,
            'image' => $imageName,
            'name'  => auth('employer')->user()->name,
            'post_type' => 'job_offer',
            'employer_id' => auth('employer')->id(),
            'is_active' => 1,
        ];

        $this->api->update($postId, $payload);

        return redirect()->route('employer.posts.show', $postId)
            ->with('status', 'Post updated successfully!');
    }


    public function editUser(Post $post)
    {
        if (!auth()->check() || auth()->id() !== $post->user_id) {
            abort(403);
        }

        $sections = ResumeBlueprint::parse($post->body ?? '');

        return view('edit-post', compact('post', 'sections'));
    }

    public function updateUser(Request $request, Post $post)
    {
        if (!auth()->check() || auth()->id() !== $post->user_id) {
            abort(403);
        }

        $validated = $request->validate([
            'title'          => ['required','string','max:255'],
            'summary'        => ['required','string','max:600'],
            'highlights'     => ['nullable','string','max:600'],
            'ideal_role'     => ['required','string','max:400'],
            'collaboration'  => ['nullable','string','max:400'],
            'availability'   => ['nullable','string','max:300'],
            'education'      => ['nullable','string','max:255'],
            'skills'         => ['required','array','min:1'],
            'skills.*'       => ['string','max:100'],
            'additional_links' => ['nullable','url'],
            'image'          => ['nullable','file','mimes:jpg,jpeg,png,gif,webp','max:2048'],
            'resume'         => ['nullable','file','mimes:pdf,doc,docx','max:2048'],
        ]);

        $body = ResumeBlueprint::build([
            'summary'       => $validated['summary'],
            'highlights'    => $validated['highlights'] ?? null,
            'ideal_role'    => $validated['ideal_role'],
            'collaboration' => $validated['collaboration'] ?? null,
            'availability'  => $validated['availability'] ?? null,
        ]);

        $imageName = $post->image;
        $resumeName = $post->resume;

        if ($request->hasFile('image')) {
            $imageName = basename($request->file('image')->store('public/posts'));
        }
        if ($request->hasFile('resume')) {
            $resumeName = basename($request->file('resume')->store('public/resumes'));
        }

        $payload = [
            'title' => $validated['title'],
            'body'  => $body,
            'education' => $validated['education'] ?? null,
            'skills'    => implode(', ', $validated['skills']),
            'additional_links' => $validated['additional_links'] ?? null,
            'image'  => $imageName,
            'resume' => $resumeName,
            'name'   => auth()->user()->name,
            'post_type' => 'resume',
            'user_id'   => auth()->id(),
            'is_active' => 1,
        ];

        $this->api->update($post->id, $payload);

        return redirect()->route('posts.show', $post->id)
            ->with('status', 'Resume updated successfully!');
    }



    // app/Http/Controllers/PostController.php

    public function close($postId)
    {
        $postArr = $this->api->get($postId);
        if (!$postArr || ($postArr['post_type'] ?? null) !== 'job_offer') abort(404);
        if ((int)($postArr['employer_id'] ?? 0) !== auth('employer')->id()) abort(403);

        // Convert published_at to the format your posts service expects
        $publishedAt = isset($postArr['published_at'])
            ? Carbon::parse($postArr['published_at'])->format('Y-m-d H:i:s')
            : now()->toDateTimeString();

        // Build ONLY the fields your posts service validates/fills
        $payload = [
            'title'            => $postArr['title']            ?? '',
            'body'             => $postArr['body']             ?? '',
            'name'             => $postArr['name']             ?? '',
            'skills'           => $postArr['skills']           ?? '',
            'location'         => $postArr['location']         ?? null,
            'position'         => $postArr['position']         ?? null,
            'salary'           => $postArr['salary']           ?? null,
            'image'            => $postArr['image']            ?? null,
            'additional_links' => $postArr['additional_links'] ?? null,
            'post_type'        => $postArr['post_type']        ?? 'job_offer',
            'employer_id'      => $postArr['employer_id']      ?? null,
            'user_id'          => $postArr['user_id']          ?? null,
            'published_at'     => $publishedAt,

            // close it
            'closed_at'        => now()->format('Y-m-d H:i:s'),
            // do NOT send is_active unless your posts service fillable rules allow it
            // 'is_active'     => 0,
        ];

        // Full PUT (your API requires full payload on update)
        $this->api->update($postId, $payload);

        return back()->with('status', 'Post successfully closed.');
    }





}
