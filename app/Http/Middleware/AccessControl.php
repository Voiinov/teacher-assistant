<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\User;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class AccessControl
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // $request->getRequestUri())
        if (Auth::check()) {
            if(User::hasRoleName("admin") || User::hasPermissions(Route::currentRouteName())) {
                return $next($request);
            }else{
                abort(403, __('Access denied'));
            }
        }
        
        return redirect()->route("login");

    }
}
