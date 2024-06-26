<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
    public function store(Request $request)
    {
        $credentials = $request->only('email', 'password');

        // Attempt to log in as a regular user
        if (Auth::guard('web')->attempt($credentials)) {
            return redirect()->intended('home');
        }

        // Attempt to log in as a company
        if (Auth::guard('company')->attempt($credentials)) {
            session(['company_logged_in' => true]);
            return redirect()->intended('home');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ]);
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        if (Auth::guard('web')->check()) {
            Auth::guard('web')->logout();
        }

        if (Auth::guard('company')->check()) {
            Auth::guard('company')->logout();
            $request->session()->forget('company_logged_in');
        }

        return redirect('/');
    }

}
