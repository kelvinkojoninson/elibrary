<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class CheckLogin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();

        // Check if the user's status is 'PENDING' or 'SUSPENDED'
        if (in_array($user->status, ['INACTIVE', 'SUSPENDED'])) {
            Auth::logout();
            return redirect('/login')->with('error', 'Your account is pending approval! Please try again later.')->withInput();
        }

        // If all checks pass, proceed to the next middleware or route handler
        return $next($request);
    }
}
