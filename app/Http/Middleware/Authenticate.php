<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */
    protected function redirectTo($request)
    {
        if (! $request->expectsJson()) {
            // 👇 This checks the actual guard failing
            $guard = array_key_first($this->guards ?? ['web']);

            if ($guard === 'employer') {
                return route('employer.login');
            }

            return route('login');
        }
    }

}
