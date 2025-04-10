<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use App\Exceptions\CustomExceptions;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, $role)
    {
        if (!Auth::guard('jwt')->check() || Auth::guard('jwt')->user()->role->name !== $role) {
            throw  CustomExceptions::authorizationError("Unauthorized Only for . $role");
        }

        return $next($request);
    }
}
