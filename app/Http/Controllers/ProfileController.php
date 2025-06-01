<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function show()
    {
        $user = auth('employer')->check() ? auth('employer')->user() : auth()->user();
        return view('profile.show', ['user' => $user]);
    }

}
