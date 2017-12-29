<?php

namespace App\Http\Middleware;

use App\Lib\BGGData;
use App\Lib\SessionManager;
use Closure;

class AppPublic {

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        SessionManager::manageConnexionInfo($request->route()->username);

        return $next($request);
    }
}