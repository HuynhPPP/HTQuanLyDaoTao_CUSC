<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, $roles): Response
    {
        $userRole = session('role');
        if (!$userRole || !in_array($userRole, $roles)) {
            // Nếu không có quyền, chuyển hướng hoặc trả về lỗi
            return redirect()->route('home')->with('error', 'Bạn không có quyền truy cập!');
        }
        return $next($request);
    }
}
