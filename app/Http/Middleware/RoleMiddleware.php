<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
public function handle(Request $request, Closure $next, ...$roles)
    {
        if (! Auth::guard('web')->check()) {
            return redirect()->route('login')->with('error','Login dulu ya.');
        }
        $user = Auth::guard('web')->user();

            // cek role
        if (! $user->hasRole($roles)) {
            abort(403);
        }

        return $next($request);
    }

}
