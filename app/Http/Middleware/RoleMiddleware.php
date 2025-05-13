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
    public function handle(Request $request, Closure $next, $roles)
    {
        $userRole = session('role');
        $rolesArray = explode(',', $roles);

        // if (!$userRole || !in_array($userRole, $rolesArray)) {
        //     return redirect()->route('about')->with('error', 'Bạn không có quyền truy cập!');
        // }
        return $next($request);
    }
}
