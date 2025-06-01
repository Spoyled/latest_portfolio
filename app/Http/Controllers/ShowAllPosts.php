<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;

class ShowAllPosts extends Controller
{
    public function index(Request $request)
    {
        // Get filter inputs
        $sortOption = $request->input('sort', 'default');
        $salaryMin = $request->input('salary_min', null);
        $nameFilter = $request->input('name_filter', null);
        $skillsFilter = $request->input('skills_filter', null);

        // Define the base query
        if (auth('employer')->check()) {
            $allPosts = Post::where('post_type', 'resume');
        } elseif (auth()->check()) {
            $allPosts = Post::where('post_type', 'job_offer');
        } else {
            $allPosts = Post::where('featured', true);
        }

        // Apply filtering
        if ($salaryMin) {
            $allPosts->where('salary', '>=', $salaryMin);
        }
        if ($nameFilter) {
            $allPosts->where('title', 'LIKE', '%' . $nameFilter . '%');
        }
        if ($skillsFilter) {
            $allPosts->where('skills', 'LIKE', '%' . $skillsFilter . '%');
        }

        // Apply sorting
        switch ($sortOption) {
            case 'salary':
                $allPosts = $allPosts->orderBy('salary', 'desc');
                break;
            case 'name':
                $allPosts = $allPosts->orderBy('name', 'asc');
                break;
            case 'skills':
                $allPosts = $allPosts->orderBy('skills', 'asc');
                break;
            default:
                $allPosts = $allPosts->latest('published_at');
                break;
        }

        // Paginate results
        $allPosts = $allPosts->paginate(10);

        // Return the view with filtered and sorted posts
        return view('all_posts', compact('allPosts', 'sortOption', 'salaryMin', 'nameFilter', 'skillsFilter'));
    }

}
