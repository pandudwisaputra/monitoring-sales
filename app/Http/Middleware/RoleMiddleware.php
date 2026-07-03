<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RoleMiddleware
{
    public function handle(
        Request $request,
        Closure $next,
        $role
    ) {

        if (!auth()->check()) {
            return redirect()->guest(route('login'));
        }

        if (auth()->user()->role != $role) {
            if (auth()->user()->role === 'admin') {
                return redirect()->route('admin.dashboard');
            } elseif (auth()->user()->role === 'sales') {
                return redirect()->route('sales.dashboard');
            }
            return redirect('/');
        }

        return $next($request);
    }
}