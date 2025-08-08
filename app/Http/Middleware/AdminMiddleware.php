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
                ->with('error', 'Vui lòng đăng nhập với tư cách quản trị viên để tiếp tục.');
        }

        // Check if user is admin
        if (!Auth::user()->isAdmin()) {
            return redirect()->route('admin.login')
                ->with('error', 'Bạn không có quyền quản trị viên.');
        }

        return $next($request);
    }
}
