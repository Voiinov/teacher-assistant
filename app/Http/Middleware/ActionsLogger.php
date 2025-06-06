<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Controllers\ActionsController;

class ActionsLogger
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {

        $status = (new Response)->getStatusCode();

        $routeName = Route::currentRouteName();

        if($status == "200"){
            (new ActionsController)->action($request, $routeName);
        }

        return $next($request);

    }

}
