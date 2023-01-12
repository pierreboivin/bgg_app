<?php

namespace App\Http\Middleware;

use App\Lib\BGGData;
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
        $GLOBALS['parameters']['cache']['level'] = '';

        SessionManager::manageConnexionInfo($request->route()->username);

        if(!isset($GLOBALS['parameters']['general']['username'])) {
            return redirect('/login');
        } else {
            $GLOBALS['parameters']['cache']['level'] = BGGData::getLevelOfLoading();
        }

        return $next($request);
    }
}