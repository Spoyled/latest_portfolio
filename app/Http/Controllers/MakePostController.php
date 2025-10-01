<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MakePostController extends Controller
{
    public function index()
    {
        // Check if the user is an employer
        $isEmployer = auth('employer')->check();

        // Choose the correct Blade file
        if ($isEmployer) {
            return view('employer.make_post', ['isEmployer' => true]);
        } else {
            return view('make_post', ['isEmployer' => false]);
        }
    }
}
