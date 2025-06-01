<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;

class ShowAllPosts extends Controller

{
    public function index(Request $request)
    {
       
        $sortOption = $request->input('sort', 'default');
        $salaryMin = $request->input('salary_min', null);
        $nameFilter = $request->input('name_filter', null);
        $positionFilter = $request->input('position_filter', null);
        $skillsFilter = $request->input('skills_filter', null);
        $locationFilter = $request->input('location_filter', null);

      
        if (auth('employer')->check()) {
            $allPosts = Post::where('post_type', 'resume');
        } elseif (auth()->check()) {
            $allPosts = Post::where('post_type', 'job_offer')->whereNull('closed_at');
        } else {
            $allPosts = Post::where('featured', true)->where('post_type', 'job_offer')->whereNull('closed_at');
        }

     
        if ($salaryMin) {
            $allPosts->where('salary', '>=', $salaryMin);
        }
        if ($nameFilter) {
            $allPosts->where('title', 'LIKE', '%' . $nameFilter . '%');
        }
        if ($skillsFilter) {
            $allPosts->where('skills', 'LIKE', '%' . $skillsFilter . '%');
        }
        if ($locationFilter) {
            $allPosts->where('location', 'LIKE', '%' . $locationFilter . '%');
        }
        if ($positionFilter) {
            $allPosts->where('position', 'LIKE', '%' . $positionFilter . '%');
        }

     
        $allPosts = $allPosts->paginate(10);

       
        return view('all_posts', compact('allPosts', 'sortOption', 'salaryMin', 'nameFilter', 'skillsFilter', 'locationFilter', 'positionFilter'));
    }

}
