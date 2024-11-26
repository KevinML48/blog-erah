<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnsureUserHasName
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check()) {
            $user = Auth::user();

            if ((is_null($user->name) || $user->name === '') &&
                !$request->session()->has('first_time_name') &&
                !$request->isMethod('patch') &&
                !$request->routeIs('profile.update.description')
            ) {
                return redirect()->route('profile.edit')->with('first_time_name', 'true');
            }
        }

        return $next($request);

    }
}
