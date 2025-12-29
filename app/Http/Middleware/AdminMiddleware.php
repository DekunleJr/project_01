<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();
        \Log::info('AdminMiddleware check', ['user_id' => $user ? $user->id : null, 'type' => $user ? $user->type : null]);
        if (!$user || $user->type !== 'admin') {
            if ($request->expectsJson()) {
                return response()->json([
                    'error' => 'Unauthorized — Admins only.'
                ], 403);
            } else {
                return redirect('/dashboard')->with('error', 'Access denied. Admin privileges required.');
            }
        }

        return $next($request);
    }
}
