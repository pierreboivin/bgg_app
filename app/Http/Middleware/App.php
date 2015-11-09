<?php


namespace App\Http\Middleware;

use Carbon\Carbon;
use Closure;

class App {

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        setlocale(LC_TIME, config('app.locale'));
        Carbon::setLocale('fr');

        $GLOBALS['debugMode'] = false;
        //$GLOBALS['debugMode'] = 'writeDebug';

        return $next($request);
    }
}