<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class CheckUserRole
{
    public function handle($request, Closure $next, $role)
    {
        if (!Auth::check() || Auth::user()->role !== $role) {
            return redirect('/home');
        }

        return $next($request);
    }
}
