<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, $roles)
    {
        // Kiểm tra session còn tồn tại (ví dụ session 'user_id')
        if (!session()->has('user_id')) {
            return redirect()->route('login')->with('error', 'Phiên đăng nhập đã hết hạn. Vui lòng đăng nhập lại.');
        }

        // Kiểm tra vai trò
        $userRole = session('role');
        $rolesArray = explode(',', $roles);

        if (!$userRole || !in_array($userRole, $rolesArray)) {
            return redirect()->route('home')->with('error', 'Bạn không có quyền truy cập!');
        }

        return $next($request);
    }
}
