<?php

namespace App\Http\Middleware;

use App\Lib\SessionManager;
use Closure;

class AppAuth {

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        SessionManager::guestConnexion($request->route()->username);

        if(!isset($GLOBALS['parameters']['general']['username'])) {
            return redirect('/login');
        }

        return $next($request);
    }
}