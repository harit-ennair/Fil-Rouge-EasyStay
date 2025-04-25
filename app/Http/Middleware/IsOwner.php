<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class IsOwner
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {   
        
         if (Auth::check() && Auth::user()->role_id == 2 || Auth::user()->role_id == 1 ) { // Assuming 2 is the role_id for owners
            return $next($request);
         }
         return redirect('/login')->with('error', 'You do not have permission to access this page.');
    }
}
