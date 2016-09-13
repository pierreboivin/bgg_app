<?php

namespace App\Http\Middleware;

use App\Lib\BGGData;
use App\Lib\SessionManager;
use Closure;

class AppIsAdmin {

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if($GLOBALS['parameters']['general']['username'] != 'pboivin') {
            return redirect('/login');
        }

        return $next($request);
    }
}