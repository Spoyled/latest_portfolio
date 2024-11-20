<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EmployerLoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.employer-login');
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::guard('employer')->attempt($credentials, $request->filled('remember'))) {
            // Redirect employers to their dashboard
            return redirect()->route('employer.dashboard');
        }

        return redirect()->back()->withInput($request->only('email', 'remember'))->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ]);
    }





    public function logout(Request $request)
    {
        Auth::guard('employer')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('employer.login'); // Redirect to the employer login page
    }


    protected function redirectTo()
    {
        if (auth()->guard('employer')->check()) {
            return '/employer/dashboard'; // Replace with your desired route
        }

        return '/dashboard'; // Default for users
    }

}
