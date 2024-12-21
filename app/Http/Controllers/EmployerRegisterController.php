<?php

namespace App\Http\Controllers;

use App\Models\Employer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class EmployerRegisterController extends Controller
{
    public function showRegistrationForm()
    {
        return view('auth.employer-register');
    }

    public function register(Request $request)
    {
        // Validate the input and enforce the unique email across tables
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                'unique:employers,email',
            ],
            'password' => 'required|string|min:8|confirmed',
        ]);

        // Create the employer record
        $employer = Employer::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // Automatically log in the employer after registration
        auth()->guard('employer')->login($employer);

        // Redirect to the employer dashboard or login page
        return redirect()->route('employer.login')->with('success', 'Registration successful. Please log in.');
    }
}
