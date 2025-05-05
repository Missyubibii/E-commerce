<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        // Check if user is logged in
        if (!Auth::check()) {
            return redirect()->route('admin.login')
                ->with('error', 'Please login as admin to continue.');
        }

        // Check if user is admin
        if (!Auth::user()->isAdmin()) {
            return redirect()->route('admin.login')
                ->with('error', 'You do not have admin privileges.');
        }

        return $next($request);
    }
}
