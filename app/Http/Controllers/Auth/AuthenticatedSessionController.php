<?php

namespace App\Http\Controllers\Auth;

use App\Events\UserLogs;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        // Generate the api access token
        $token = $request->user()->createToken(bin2hex(random_bytes(10)))->plainTextToken;

        // Store in api token session
        Session::put('api_token', $token);

        // Update the 'last_login' field of the user with the provided ID to the current date and time
        User::where('id', $request->user()->id)->update(['last_login' => date('Y-m-d H:i:s')]);

        // Trigger a 'UserLogs' event with the user ID, event type, description, endpoint, and response code
        event(new UserLogs(
            $request->user()->userid,
            'Login',
            'User logged in successfully',
            'POST /login',
            '200'
        ));

        return redirect()->intended(RouteServiceProvider::HOME);
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
