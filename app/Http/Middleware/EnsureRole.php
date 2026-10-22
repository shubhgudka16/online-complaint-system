<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureRole
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        if (! $request->user()) {
            return redirect()->guest(route('login'));
        }

        if (! empty($roles) && ! in_array($request->user()->role, $roles, true)) {
            abort(403, 'Unauthorized access: You do not have sufficient privileges to access this page.');
        }

        return $next($request);
    }
}
